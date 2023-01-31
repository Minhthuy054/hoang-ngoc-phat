<?php
/**
 * Member Listings
 *
 */
if ( ! class_exists('Wp_dp_Member_Listings') ) {

    class Wp_dp_Member_Listings {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_enqueue_scripts', array( $this, 'wp_dp_filters_element_scripts' ), 11);

            add_action('wp_ajax_wp_dp_member_listings', array( $this, 'wp_dp_member_listings_callback' ), 11, 1);
            add_action('wp_ajax_nopriv_wp_dp_member_listings', array( $this, 'wp_dp_member_listings_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_member_published_listings', array( $this, 'wp_dp_member_published_listings_callback' ), 11, 1);
            add_action('wp_ajax_nopriv_wp_dp_member_published_listings', array( $this, 'wp_dp_member_published_listings_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_member_pending_listings', array( $this, 'wp_dp_member_pending_listings_callback' ), 11, 1);
            add_action('wp_ajax_nopriv_wp_dp_member_pending_listings', array( $this, 'wp_dp_member_pending_listings_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_member_expired_listings', array( $this, 'wp_dp_member_expired_listings_callback' ), 11, 1);
            add_action('wp_ajax_nopriv_wp_dp_member_expired_listings', array( $this, 'wp_dp_member_expired_listings_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_member_promoted_listings', array( $this, 'wp_dp_member_promoted_listings_callback' ), 11, 1);
            add_action('wp_ajax_nopriv_wp_dp_member_promoted_listings', array( $this, 'wp_dp_member_promoted_listings_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_member_prop_notes', array( $this, 'wp_dp_member_listings_notes_callback' ), 11, 1);
            add_action('wp_ajax_nopriv_wp_dp_member_prop_notes', array( $this, 'wp_dp_member_listings_notes_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_member_listing_delete', array( $this, 'delete_user_listing' ));
            add_action('wp_ajax_wp_dp_removed_prop_removed', array( $this, 'delete_user_listing_notes' ));
            add_action('wp_ajax_wp_dp_listing_sold_check', array( $this, 'wp_dp_listing_sold_check' ));
            add_action('wp_ajax_wp_dp_update_listing_visibility', array( $this, 'wp_dp_update_listing_visibility_callback' ));
        }

        public function wp_dp_filters_element_scripts() {
            wp_enqueue_style('daterangepicker');
            wp_enqueue_script('daterangepicker-moment');
            wp_enqueue_script('daterangepicker');
            wp_enqueue_script('wp-dp-filters-functions');
        }

        public function wp_dp_listing_sold_check() {

            $prop_id = isset($_POST['prop_id']) ? $_POST['prop_id'] : '';

            $msg = wp_dp_plugin_text_srt('wp_dp_listing_sold_action_failed_notice');
            $html = '';
            $type = 'error';
            if ( $prop_id != '' ) {
                update_post_meta($prop_id, 'wp_dp_listing_sold', 'yes');
                $msg = wp_dp_plugin_text_srt('wp_dp_listing_sold_marked_as_sold');
                $html = '<span class="prop-sold">' . wp_dp_plugin_text_srt('wp_dp_listing_sold_single_txt') . '</span>';
                $type = 'success';
            }

            echo json_encode(array( 'type' => $type, 'msg' => $msg, 'html' => $html ));
            die;
        }

        public function delete_user_listing_notes() {

            global $current_user;

            $prop_id = isset($_POST['listing_id']) ? $_POST['listing_id'] : '';

            $company_id = wp_dp_company_id_form_user_id($current_user->ID);

            $listing_notes = get_post_meta($company_id, 'listing_notes', true);

            unset($listing_notes[$prop_id]);

            update_post_meta($company_id, 'listing_notes', $listing_notes);

            echo json_encode(array( 'status' => true, 'message' => wp_dp_plugin_text_srt('wp_dp_prop_notes_prop_notes_deleted') ));
            die;
        }

        public function wp_dp_member_listings_notes_callback($member_id = '') {
            global $current_user, $wp_dp_plugin_options;
            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';
            $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 10;
            $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';
            $start = 0;
            if ( $posts_paged > 0 ) {
                $start = ( $posts_paged - 1 ) * $posts_per_page;
            }

            $member_id = wp_dp_company_id_form_user_id($current_user->ID);

            $listing_notes = get_post_meta($member_id, 'listing_notes', true);

            if ( ! empty($listing_notes) ) {
                foreach ( $listing_notes as $note_key => $noteData ) {
                    if ( $noteData['notes'] == 'undefined' ) {
                        unset($listing_notes[$note_key]);
                    }
                }
            }
            $total_res = 0;
            if ( is_array($listing_notes) && sizeof($listing_notes) > 0 ) {
                $total_res = sizeof($listing_notes);
            }

            $output_listing_notes = array_slice($listing_notes, $start, $posts_per_page);
            echo force_balance_tags($this->render_notes_view($output_listing_notes));

            $total_pages = 1;
            if ( $total_res > 0 && $posts_per_page > 0 && $total_res > $posts_per_page ) {
                $total_pages = ceil($total_res / $posts_per_page);
                $dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $dashboard_link = $dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($dashboard_page, 'page') : '';
                $this_url = $dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'prop_notes' ), $dashboard_link) : '';
                wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'prop_notes');
            }

            wp_die();
        }

        public function render_notes_view($all_listings) {
            global $wp_dp_plugin_options, $wp_dp_form_fields_frontend;
            $has_border = ' has-border';
            if ( ! empty($all_listings) ) {
                $has_border = '';
            }
           
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="user-listing">
                        <div class="element-title<?php echo wp_dp_allow_special_char($has_border); ?>">
                            <h4><?php echo wp_dp_plugin_text_srt('wp_dp_prop_notes_listings_notes') ?></h4>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="wp-dp-dev-user-listing-notes" class="user-favorite-list" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"> 
                                    <ul class="favourites-list">

                                        <?php
                                        if ( isset($all_listings) && ! empty($all_listings) ) {
                                            foreach ( $all_listings as $listing_key => $listing_data ) {

                                                $notes = isset($listing_data['notes']) ? $listing_data['notes'] : '';
                                                $listing_id = isset($listing_data['listing_id']) ? $listing_data['listing_id'] : '';
                                                if ( get_the_title($listing_id) != '' && $notes != '' ) {
                                                    ?>
                                                    <li>
                                                        <div class="suggest-list-holder">
                                                            <div class="text-holder">
                                                                <h6><a href="<?php echo get_permalink($listing_id) ?>"><?php echo get_the_title($listing_id) ?></a></h6>
                                                                <p>
                                                                    <?php
                                                                    if ( strlen($notes) > 200 ) {
                                                                        echo substr($notes, 0, 200);
                                                                        echo '<span class="expanded-txt" style="display: none;">' . substr($notes, 200, strlen($notes)) . '</span>';
                                                                        echo ' <a href="javascript:void(0)" class="expand-notes" data-sh-more="' . wp_dp_plugin_text_srt('wp_dp_prop_notes_show_more') . '" data-sh-less="' . wp_dp_plugin_text_srt('wp_dp_prop_notes_show_less') . '">' . wp_dp_plugin_text_srt('wp_dp_prop_notes_show_more') . '</a>';
                                                                    } else {
                                                                        echo force_balance_tags($notes);
                                                                    }
                                                                    ?>
                                                                </p>
                                                                <a href="javascript:void(0);" class="short-icon delete-prop-notes" data-type="notes" data-id="<?php echo absint($listing_id) ?>"><i class="icon-close"></i></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                        } else {
                                            ?>
                                            <li class="no-listing-found">
                                                <i class="icon-caution"></i>
                                                <?php echo wp_dp_plugin_text_srt('wp_dp_prop_notes_no_result_notes') ?>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * Member Listings
         * @ filter the listings based on member id
         */
        public function wp_dp_member_listings_callback($member_id = '') {
            global $current_user, $wp_dp_plugin_options;
            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';
            $member_id = wp_dp_company_id_form_user_id($current_user->ID);
            $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 1;
            $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';

            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => $posts_per_page,
                'paged' => $posts_paged,
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_member',
                        'value' => $member_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'delete',
                        'compare' => '!=',
                    ),
                ),
            );

            $args = wp_dp_filters_query_args($args);
            $custom_query = new WP_Query($args);
            $total_posts = $custom_query->found_posts;
            $all_listings = $custom_query->posts;
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_my_prop'); ?></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_statics_all_listings'); ?></li>
            </ul>
            <div class="user-dashboard-background">
                <?php
                echo force_balance_tags($this->render_view($all_listings, 'all'));
                ?>
            </div>
            <?php
            wp_reset_postdata();

            $total_pages = 1;
            if ( $total_posts > 0 && $posts_per_page > 0 && $total_posts > $posts_per_page ) {
                $total_pages = ceil($total_posts / $posts_per_page);
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $this_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'listings' ), $wp_dp_dashboard_link) : '';
                wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'listings');
            }

            wp_die();
        }

        public function wp_dp_member_published_listings_callback($member_id = '') {
            global $current_user, $wp_dp_plugin_options;
            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';
            $member_id = wp_dp_company_id_form_user_id($current_user->ID);
            $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 1;
            $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';

            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => $posts_per_page,
                'paged' => $posts_paged,
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_member',
                        'value' => $member_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'active',
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_expired',
                        'value' => strtotime(date('Y-m-d')),
                        'compare' => '>=',
                    ),
                ),
            );

            $args = wp_dp_filters_query_args($args);
            $custom_query = new WP_Query($args);
            $total_posts = $custom_query->found_posts;
            $all_listings = $custom_query->posts;
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_my_prop'); ?></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_published_listings'); ?></li>
            </ul>
            <div class="user-dashboard-background">
                <?php
                echo force_balance_tags($this->render_view($all_listings, 'published'));
                ?>
            </div>
            <?php
            wp_reset_postdata();

            $total_pages = 1;
            if ( $total_posts > 0 && $posts_per_page > 0 && $total_posts > $posts_per_page ) {
                $total_pages = ceil($total_posts / $posts_per_page);
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $this_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'published_listings' ), $wp_dp_dashboard_link) : '';
                wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'published_listings');
            }

            wp_die();
        }

        public function wp_dp_member_pending_listings_callback($member_id = '') {
            global $current_user, $wp_dp_plugin_options;
            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';
            $member_id = wp_dp_company_id_form_user_id($current_user->ID);
            $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 1;
            $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';

            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => $posts_per_page,
                'paged' => $posts_paged,
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_member',
                        'value' => $member_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'awaiting-activation',
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_expired',
                        'value' => strtotime(date('Y-m-d')),
                        'compare' => '>=',
                    )
                ),
            );

            $args = wp_dp_filters_query_args($args);
            $custom_query = new WP_Query($args);
            $total_posts = $custom_query->found_posts;
            $all_listings = $custom_query->posts;
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_my_prop'); ?></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_pending_listings'); ?></li>
            </ul>
            <div class="user-dashboard-background">
                <?php
                echo force_balance_tags($this->render_view($all_listings, 'pending'));
                ?>
            </div>
            <?php
            wp_reset_postdata();

            $total_pages = 1;
            if ( $total_posts > 0 && $posts_per_page > 0 && $total_posts > $posts_per_page ) {
                $total_pages = ceil($total_posts / $posts_per_page);
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $this_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'pending_listings' ), $wp_dp_dashboard_link) : '';
                wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'pending_listings');
            }

            wp_die();
        }

        /*
         * promoted listing
         */

        public function wp_dp_member_promoted_listings_callback($member_id = '') {
            global $current_user, $wp_dp_plugin_options;
            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';
            $member_id = wp_dp_company_id_form_user_id($current_user->ID);
            $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 1;
            $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';


            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => $posts_per_page,
                'paged' => $posts_paged,
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_member',
                        'value' => $member_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'delete',
                        'compare' => '!=',
                    ),
                    array(
                        'key' => 'wp_dp_promotions',
                        'value' => '',
                        'compare' => '!=',
                    ),
                ),
            );
            $args = wp_dp_filters_query_args($args);
            $custom_query = new WP_Query($args);
            $total_posts = $custom_query->found_posts;
            $all_listings = $custom_query->posts;
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_my_prop'); ?></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_promoted_listing'); ?></li>
            </ul>
            <div class="user-dashboard-background">
                <?php
                echo force_balance_tags($this->render_view($all_listings, 'promoted'));
                ?>
            </div>
            <?php
            wp_reset_postdata();

            $total_pages = 1;
            if ( $total_posts > 0 && $posts_per_page > 0 && $total_posts > $posts_per_page ) {
                $total_pages = ceil($total_posts / $posts_per_page);
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $this_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'expired_listings' ), $wp_dp_dashboard_link) : '';
                wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'expired_listings');
            }

            wp_die();
        }

        /*
         * end promoted listing
         */

        public function wp_dp_member_expired_listings_callback($member_id = '') {
            global $current_user, $wp_dp_plugin_options;
            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';
            $member_id = wp_dp_company_id_form_user_id($current_user->ID);
            $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 1;
            $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';

            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => $posts_per_page,
                'paged' => $posts_paged,
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_member',
                        'value' => $member_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_expired',
                        'value' => strtotime(date('Y-m-d')),
                        'compare' => '<',
                    ),
                    array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'delete',
                        'compare' => '!=',
                    )
                ),
            );

            $args = wp_dp_filters_query_args($args);
            $custom_query = new WP_Query($args);
            $total_posts = $custom_query->found_posts;
            $all_listings = $custom_query->posts;
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_my_prop'); ?></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_expired_listings'); ?></li>
            </ul>
            <div class="user-dashboard-holder2">
                <div class="user-dashboard-background">
                    <?php
                    echo force_balance_tags($this->render_view($all_listings, 'expired'));
                    ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();

            $total_pages = 1;
            if ( $total_posts > 0 && $posts_per_page > 0 && $total_posts > $posts_per_page ) {
                $total_pages = ceil($total_posts / $posts_per_page);
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $this_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'expired_listings' ), $wp_dp_dashboard_link) : '';
                wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'expired_listings');
            }

            wp_die();
        }

        /**
         * Member Listings HTML render
         * @ HTML before and after the listing items
         */
        public function render_view($all_listings, $listing_type = 'all') {
            global $wp_dp_plugin_options, $wp_dp_form_fields_frontend;
            wp_enqueue_script('wp-dp-filters-functions');

            $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
            $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';

            $wp_dp_listing_add_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'add_listing' ), $wp_dp_dashboard_link) : '#';

            $date_range = isset($_POST['date_range']) ? $_POST['date_range'] : '';
            $has_border = ' has-border';
            if ( ! empty($all_listings) ) {
                $has_border = '';
            }
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <div class="user-listing">
                        <div class="element-title right-filters-row<?php echo wp_dp_allow_special_char($has_border); ?>">
                            <h4>
                                <?php
                                if ( $listing_type == 'published' ) {
                                    echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_published_listings');
                                } else if ( $listing_type == 'pending' ) {
                                    echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_pending_listings');
                                } else if ( $listing_type == 'expired' ) {
                                    echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_expired_listings');
                                } else if ( $listing_type == 'promoted' ) {
                                    echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_promoted_listing');
                                } else {
                                    echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_statics_all_listings');
                                }
                                ?>
                            </h4>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="wp-dp-dev-user-listing" class="user-list" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"> 

                                    <ul class="panel-group" style="min-height: 415px;">
                                        <?php
                                        if ( isset($all_listings) && ! empty($all_listings) ) {

                                            foreach ( $all_listings as $listing_data ) {
                                                echo force_balance_tags($this->render_list_item_view($listing_data, $listing_type));
                                            }
                                        } else {
                                            ?>
                                            <li class="no-listing-found">
                                                <i class="icon-caution"></i>
                                                <?php
                                                if ( $listing_type == 'published' ) {
                                                    echo wp_dp_plugin_text_srt('wp_dp_dashboard_published_listings_not_found');
                                                } else if ( $listing_type == 'pending' ) {
                                                    echo wp_dp_plugin_text_srt('wp_dp_dashboard_pending_listings_not_found');
                                                } else if ( $listing_type == 'expired' ) {
                                                    echo wp_dp_plugin_text_srt('wp_dp_dashboard_expired_listings_not_found');
                                                } else if ( $listing_type == 'promoted' ) {
                                                    echo wp_dp_plugin_text_srt('wp_dp_dashboard_promoted_listings_not_found');
                                                } else {
                                                    echo wp_dp_plugin_text_srt('wp_dp_dashboard_all_listings_not_found');
                                                }
                                                ?>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * Member Listings Items HTML render
         * @ HTML for listing items
         */
        public function render_list_item_view($listing_data, $view_listing = '') {
            global $post, $wp_dp_plugin_options, $wp_dp_html_fields;

            $wp_dp_create_listing_page = isset($wp_dp_plugin_options['wp_dp_price_plan_page']) ? $wp_dp_plugin_options['wp_dp_price_plan_page'] : '';

            $post = $listing_data;
            setup_postdata($post);

            $listing_post_on = get_post_meta(get_the_ID(), 'wp_dp_listing_posted', true);
            $listing_post_expiry = get_post_meta(get_the_ID(), 'wp_dp_listing_expired', true);
            $listing_status = get_post_meta(get_the_ID(), 'wp_dp_listing_status', true);
            $listing_visibility = get_post_meta(get_the_ID(), 'wp_dp_listing_visibility', true);
            $wp_dp_listing_member = get_post_meta(get_the_ID(), 'wp_dp_listing_member', true);

            $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
            $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';

            $wp_dp_listing_update_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'add_listing', 'listing_id' => get_the_ID() ), $wp_dp_dashboard_link) : '#';
            $current_user = wp_get_current_user();
            $member_id = wp_dp_company_id_form_user_id($current_user->ID);
            $wp_dp_listing_type = get_post_meta(get_the_ID(), 'wp_dp_listing_type', true);
            if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                $listing_type_id = $listing_type_post->ID;
            $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
            $wp_dp_post_loc_address_listing = get_post_meta(get_the_ID(), 'wp_dp_post_loc_address_listing', true);

            /*
             * for promoted listing
             */
            $already_purchased = false;
            if ( $view_listing == 'promoted' ) {
                $promotions = get_post_meta(get_the_ID(), 'wp_dp_promotions', true);
                $title = array();
                if ( ! empty($promotions) ) {
                    foreach ( $promotions as $key => $promotion ) {
                        $expiry_date = isset($promotion['expiry']) ? $promotion['expiry'] : '';
                        if ( $expiry_date >= date('Y-m-d') || $expiry_date == 'unlimitted' ) {
                            $already_purchased = true;
                        }
                    }
                }
            }
            if ( $already_purchased == false && $view_listing == 'promoted' ) {
                return;
            }

            /*
             * end for promoted listing
             */
            ?>
            <li id="user-listing-<?php echo absint(get_the_ID()); ?>" class="alert" data-id="<?php echo esc_attr(get_the_ID()); ?>">
                <div class="panel panel-default">
                    <div class="panel-heading"> 
                        <div class="img-holder">
                            <figure>
                                <?php
                                if ( function_exists('listing_gallery_first_image') ) {
                                    $gallery_image_args = array(
                                        'listing_id' => get_the_ID(),
                                        'size' => 'thumbnail',
                                        'class' => '',
                                        'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg')
                                    );
                                    $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                    echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                }
                                ?>
                            </figure>
                            <div class="listing-label-caption">
                                <h6><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo get_the_title(); ?></a></h6>

                                <?php
                                $wp_dp_listing_category = get_post_meta(get_the_ID(), 'wp_dp_listing_category', true);
                                $wp_dp_cate_str =  isset( $wp_dp_cate_str ) ? $wp_dp_cate_str : '';
                                if ( ! empty($wp_dp_listing_category) && is_array($wp_dp_listing_category) ) {
                                    $comma_flag = 0;
                                    foreach ( $wp_dp_listing_category as $cate_slug => $cat_val ) {
                                        $wp_dp_cate = get_term_by('slug', $cat_val, 'listing-category');

                                        if ( ! empty($wp_dp_cate) ) {
                                            $cate_link = wp_dp_listing_category_link($listing_type_id, $cat_val);
                                            if ( $comma_flag != 0 ) {
                                                $wp_dp_cate_str .= ', ';
                                            }
                                            $wp_dp_cate_str .= '<a href="' . $cate_link . '">' . $wp_dp_cate->name . '</a>';
                                            $comma_flag ++;
                                        }
                                    }
                                }
                                echo force_balance_tags($wp_dp_cate_str);
                                do_action('wp_dp_listings_caption_area', get_the_ID());
                                ?>
                            </div>    
                        </div>  
                        <div class="text-holder">
                            <div class="date-status-holder">
                                <?php
                                $dispaly_array = array(
                                    'awaiting-activation' => wp_dp_plugin_text_srt('wp_dp_listing_awaiting_activation'),
                                    'active' => wp_dp_plugin_text_srt('wp_dp_listing_active'),
                                    'inactive' => wp_dp_plugin_text_srt('wp_dp_listing_inactive'),
                                    'delete' => wp_dp_plugin_text_srt('wp_dp_listing_delete'),
                                );
                                if ( $listing_status == 'active' || $listing_status == 'awaiting-activation' ) {
                                    ?>
                                    <span class="expire-date"><?php echo esc_html($listing_post_expiry != '' ? date_i18n(get_option('date_format'), $listing_post_expiry) : '' ); ?></span>
                                    <?php
                                } else {
                                    ?>
                                    <span class="expire-date">-</span>
                                    <?php
                                }
                                ?>
                                <?php if ( $view_listing == 'expired' ) { ?>
                                    <div class="listing-status"><a href="<?php echo add_query_arg('listing_id', get_the_ID(), wp_dp_wpml_lang_page_permalink($wp_dp_create_listing_page, 'page')); ?>" class="renew-listing"><?php echo wp_dp_plugin_text_srt('wp_dp_renew_listing'); ?></a></div>
                                <?php } ?>
                                <div class="listing-status"><span class="<?php echo strtolower($listing_status); ?>"><?php echo $dispaly_array[$listing_status]; ?></span></div>
                            </div>

                            <?php if ( $view_listing != 'promoted' ) { ?>

                                <div class="listing-option-dropdown">
                                    <ul>
                                        <li> <a  href="javascript:void(0);"> <i class="icon-cog4"></i> </a>
                                            <ul class="listing-dropdown">
                                                <li>
                                                    <?php
                                                    $listing_visibility = (isset($listing_visibility) && $listing_visibility != '') ? $listing_visibility : 'public';
                                                    if ( $listing_visibility == 'public' ) {
                                                        $listing_visibility = 'public';
                                                        $icon_class = 'icon-eye';
                                                        $icon_color = 'green';
                                                    } else {
                                                        $icon_class = 'icon-eye-blocked';
                                                        $listing_visibility = 'invisible';
                                                        $icon_color = 'red';
                                                    }
                                                    $visibility_options = array(
                                                        'public' => wp_dp_plugin_text_srt('wp_dp_add_listing_public'),
                                                        'invisible' => wp_dp_plugin_text_srt('wp_dp_add_listing_invisible'),
                                                    );
                                                    $listing_visibility = isset($visibility_options[$listing_visibility]) ? $visibility_options[$listing_visibility] : $listing_visibility;
                                                    ?>
                                                    <div class="listing-visibility">
                                                        <a class="listing-visibility-update" id="listing-visibility-<?php echo absint(get_the_ID()); ?>" data-id="<?php echo absint(get_the_ID()); ?>" href="javascript:void(0);">
                                                            <i class="<?php echo esc_html($icon_class); ?>" style="color:<?php echo esc_html($icon_color); ?>"></i>
                                                            <span style="color:<?php echo esc_html($icon_color); ?>"><?php echo esc_html($listing_visibility); ?></span>
                                                        </a>
                                                    </div>
                                                </li>
                                                <li> 
                                                    <div class="listing-edit">
                                                        <a href="<?php echo esc_url_raw($wp_dp_listing_update_url) ?>"><i class="icon-mode_edit"></i>
                                                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_memberlist_edit'); ?></span></a>
                                                    </div>
                                                </li>
                                                <li> 
                                                    <div class="listing-del">
                                                        <a href="javascript:void(0);" data-id="<?php echo absint(get_the_ID()); ?>" class="close-member wp-dp-dev-listing-delete">
                                                            <i class="icon-trash-o"></i>
                                                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_attachment_remove'); ?></span>
                                                        </a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php
                                                    if ( ($listing_status == 'active' || $listing_status == 'awaiting-activation') && $listing_post_expiry != '' && $listing_post_expiry > strtotime(current_time('Y-m-d', 1)) ) {
                                                        ?>
                                                        <div class="sold-listing-box">
                                                            <?php
                                                            if ( wp_dp_is_listing_sold(get_the_ID()) ) {
                                                                ?>
                                                                <span class="prop-sold"><i class="icon-money_off"></i><?php echo wp_dp_plugin_text_srt('wp_dp_listing_already_sold_txt') ?></span>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <input type="checkbox" id="listing-sold-check-<?php echo get_the_ID() ?>" data-id="<?php echo get_the_ID() ?>" class="listing-sold-check" style="display:none;"/>
                                                                <i class="icon-check"></i>&nbsp;<label for="listing-sold-check-<?php echo get_the_ID() ?>"><span><?php echo wp_dp_plugin_text_srt('wp_dp_listing_sold_mark_as_sold') ?></span></label>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>

                            <?php } ?>

                        </div>
                        <?php
                        $listing_favourites = get_post_meta(get_the_ID(), 'wp_dp_listing_favourites', true);
                        $listing_favourites = (isset($listing_favourites) && $listing_favourites != '') ? $listing_favourites : 0;
                        $listing_views_count = get_post_meta(get_the_ID(), 'wp_dp_listing_views_count', true);
                        $listing_views_count = (isset($listing_views_count) && $listing_views_count != '') ? $listing_views_count : 0;

                        $listing_expired = get_post_meta(get_the_ID(), 'wp_dp_listing_expired', true);
                        $diff = $listing_expired - strtotime(date('d-m-Y'));
                        $daysleft = floor($diff / (60 * 60 * 24));

                        $args = array(
                            'post_type' => 'listing_enquiries',
                            'post_status' => 'publish',
                            'posts_per_page' => '1',
                            'fields' => 'ids',
                            'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'wp_dp_listing_member',
                                    'value' => $member_id,
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'wp_dp_listing_id',
                                    'value' => get_the_ID(),
                                    'compare' => '=',
                                )
                            ),
                        );
                        $enquiry_query = new WP_Query($args);



                        $total_enquiries = $enquiry_query->found_posts;
                        wp_reset_postdata();

                        $args = array(
                            'post_type' => 'listing_viewings',
                            'post_status' => 'publish',
                            'posts_per_page' => '1',
                            'fields' => 'ids',
                            'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'wp_dp_listing_member',
                                    'value' => $member_id,
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'wp_dp_listing_id',
                                    'value' => get_the_ID(),
                                    'compare' => '=',
                                )
                            ),
                        );
                        $order_query = new WP_Query($args);
                        $total_viewings = $order_query->found_posts;
                        wp_reset_postdata();

                        $post = get_post(get_the_ID());
                        $post_slug = $post->post_name;
                        $reviews_args = array(
                            'post_type' => 'wp_dp_reviews',
                            'post_status' => 'publish',
                            'posts_per_page' => '-1',
                            'fields' => 'ids',
                            'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'company_id',
                                    'value' => $member_id,
                                    'compare' => '!=',
                                ),
                                array(
                                    'key' => 'post_id',
                                    'value' => $post_slug,
                                    'compare' => 'LIKE',
                                )
                            ),
                        );

                        $reviews_query = new WP_Query($reviews_args);
                        $total_reviews = $reviews_query->found_posts;
                        wp_reset_postdata();
                        if ( $view_listing != 'promoted' ) {
                            ?>
                            <div class="received-enquiries-viewings-holder">
                                <ul class="enquiries-viewings-links">
                                    <li class="user_dashboard_ajax favorite" data-param="<?php echo absint(get_the_ID()); ?>" id="wp_dp_member_favourites_<?php echo absint(get_the_ID()); ?>" data-queryvar="dashboard=favoget_the_IDurites">
                                        <b class="count-received-enquiries"><?php echo absint($listing_favourites); ?></b>
                                        <a href="javascript:void(0);"><?php echo wp_dp_cs_allow_special_char($listing_favourites) == 1 ? wp_dp_plugin_text_srt('wp_dp_dashboard_user_listing_favorite') : wp_dp_plugin_text_srt('wp_dp_dashboard_user_listing_favorites'); ?></a>
                                    </li>
                                    <li class="user_dashboard_ajax reviews" data-param="<?php echo absint(get_the_ID()); ?>" id="wp_dp_publisher_my_reviews_<?php echo absint(get_the_ID()); ?>" data-queryvar="dashboard=my_reviews">
                                        <b class="count-received-reviews"><?php echo absint($total_reviews); ?></b>
                                        <a href="javascript:void(0);"><?php echo wp_dp_cs_allow_special_char($total_reviews) == 1 ? wp_dp_plugin_text_srt('wp_dp_listing_review') : wp_dp_plugin_text_srt('wp_dp_reviews_name'); ?></a>
                                    </li>
                                    <li class="views">
                                        <b class="count-received-viewings"><?php echo absint($listing_views_count); ?></b>
                                        <a href="javascript:void(0);"><?php echo wp_dp_cs_allow_special_char($listing_views_count) == 1 ? wp_dp_plugin_text_srt('wp_dp_features_view') : wp_dp_plugin_text_srt('wp_dp_dashboard_user_listing_views'); ?></a>
                                    </li>
                                    <li class="user_dashboard_ajax inbox" data-param="<?php echo absint(get_the_ID()); ?>" id="wp_dp_member_received_enquiries_<?php echo absint(get_the_ID()); ?>" data-queryvar="dashboard=enquiries_received">
                                        <b class="count-received-enquiries"><?php echo absint($total_enquiries); ?></b>
                                        <a href="javascript:void(0);"><?php echo wp_dp_cs_allow_special_char($total_enquiries) == 1 ? wp_dp_plugin_text_srt('wp_dp_enquiry_detail_msg') : wp_dp_plugin_text_srt('wp_dp_member_dashboard_enquires'); ?></a>
                                    </li>
                                    <li class="user_dashboard_ajax orders" data-param="<?php echo absint(get_the_ID()); ?>" id="wp_dp_member_received_viewings" data-queryvar="dashboard=viewings_received">
                                        <b class="count-received-viewings"><?php echo absint($total_viewings); ?></b>
                                        <a href="javascript:void(0);"><?php echo wp_dp_cs_allow_special_char($total_viewings) == 1 ? wp_dp_plugin_text_srt('wp_dp_order') : wp_dp_plugin_text_srt('wp_dp_dashboard_user_listing_orders'); ?></a>
                                    </li>
                                    <li class="days">
                                        <?php
                                        $expired_class = ' class="expired"';
                                        if ( $daysleft >= 0 ) {
                                            $expired_class = '';
                                            ?>
                                            <b class="count-received-viewings"><?php echo wp_dp_cs_allow_special_char($daysleft); ?></b>
                                        <?php } ?>
                                        <a href="javascript:void(0);"<?php echo wp_dp_cs_allow_special_char($expired_class); ?>>
                                            <?php
                                            if ( $daysleft < 0 ) {
                                                echo wp_dp_plugin_text_srt('wp_dp_listing_listing_expired');
                                            } elseif ( $daysleft == 1 ) {
                                                echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_listing_day_left');
                                            } else {
                                                echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_listing_days_left');
                                            }
                                            ?>  
                                        </a>
                                    </li>
                                    <?php do_action('wp_dp_listings_quick_links', get_the_ID()); ?>

                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </li>



            <?php
            wp_reset_postdata();
        }

        /**
         * Deleting user listing from dashboard
         * @Delete Listing
         */
        public function delete_user_listing() {
            global $current_user;
            $listing_id = isset($_POST['listing_id']) ? $_POST['listing_id'] : '';
            $wp_dp_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            $member_id = wp_dp_company_id_form_user_id($current_user->ID);

            if ( is_user_logged_in() && $member_id == $wp_dp_member_id ) {
                update_post_meta($listing_id, 'wp_dp_listing_status', 'delete');
                $listing_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                if ( $listing_member_id != '' ) {
                    do_action('wp_dp_plublisher_listings_decrement', $listing_member_id);
                }
                echo json_encode(array( 'delete' => 'true' ));
            } else {
                echo json_encode(array( 'delete' => 'false' ));
            }
            die;
        }

        public function wp_dp_update_listing_visibility_callback() {

            global $current_user;
            $member_id = wp_dp_company_id_form_user_id($current_user->ID);

            $listing_id = isset($_POST['listing_id']) ? $_POST['listing_id'] : '';
            $visibility_status = isset($_POST['visibility_status']) ? $_POST['visibility_status'] : '';

            $response = array( 'msg' => wp_dp_plugin_text_srt('wp_dp_listing_invisible_update_error'), 'type' => 'error', 'label' => $visibility_status );
            if ( $listing_id ) {
                $wp_dp_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                if ( is_user_logged_in() && $member_id == $wp_dp_member_id ) {
                    $listing_visibility = get_post_meta($listing_id, 'wp_dp_listing_visibility', true);
                    if ( $listing_visibility == 'public' ) {
                        update_post_meta($listing_id, 'wp_dp_listing_visibility', 'invisible');
                        $response = array( 'msg' => wp_dp_plugin_text_srt('wp_dp_listing_invisible_update_success'), 'type' => 'success', 'label' => wp_dp_plugin_text_srt('wp_dp_add_listing_invisible'), 'icon' => 'icon-eye-blocked', 'value' => 'invisible' );
                    } else {
                        update_post_meta($listing_id, 'wp_dp_listing_visibility', 'public');
                        $response = array( 'msg' => wp_dp_plugin_text_srt('wp_dp_listing_invisible_update_success'), 'type' => 'success', 'label' => wp_dp_plugin_text_srt('wp_dp_add_listing_public'), 'icon' => 'icon-eye', 'value' => 'public' );
                    }
                }
            }
            echo json_encode($response);
            die;
        }

    }

    global $wp_dp_member_listings;
    $wp_dp_member_listings = new Wp_dp_Member_Listings();
}
