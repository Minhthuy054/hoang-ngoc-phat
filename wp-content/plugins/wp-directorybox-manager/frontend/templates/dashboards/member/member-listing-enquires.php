<?php
/**
 * Member Listings
 *
 */
if ( ! class_exists('Wp_dp_Member_Listing_Enquiries') ) {

    class Wp_dp_Member_Listing_Enquiries {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_ajax_wp_dp_member_enquiries', array( $this, 'wp_dp_member_enquiries_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_member_received_enquiries', array( $this, 'wp_dp_member_received_enquiries_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_change_enquiry_status', array( $this, 'wp_dp_change_enquiry_status_callback' ), 11, 1);
        }

        public function wp_dp_change_enquiry_status_callback() {
            $enquiry_id = wp_dp_get_input('enquiry_id', '');
            $type = wp_dp_get_input('type', '');

            $buyer_read_status = get_post_meta($enquiry_id, 'buyer_read_status', true);
            $seller_read_status = get_post_meta($enquiry_id, 'seller_read_status', true);

            if ( $type == 'my' ) {
                if ( $buyer_read_status == 1 ) {
                    $read_unread = 'unread';
                    update_post_meta($enquiry_id, 'buyer_read_status', false);
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_discussion_message_unread');
                } else {
                    $read_unread = 'read';
                    update_post_meta($enquiry_id, 'buyer_read_status', true);
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_discussion_message_read');
                }
            } else {
                if ( $seller_read_status == 1 ) {
                    $read_unread = 'unread';
                    update_post_meta($enquiry_id, 'seller_read_status', false);
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_discussion_message_unread');
                } else {
                    $read_unread = 'read';
                    update_post_meta($enquiry_id, 'seller_read_status', true);
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_discussion_message_read');
                }
            }
            
            
            
            
            
            

            $json['type'] = "success";
            $json['read_type'] = $read_unread;
            if ( $read_unread == 'read' ) {
                $json['label'] = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_as_read');
            } else {
                $json['label'] = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_as_unread');
            }
            echo json_encode($json);
            wp_die();
        }

        public function wp_dp_member_enquiries_callback($member_id = '') {
            global $wp_dp_plugin_options;
            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';
            $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 1;
            $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';
            // Member ID.
            if ( ! isset($member_id) || $member_id == '' ) {
                $member_id = get_current_user_id();
            }

            $member_company_id = wp_dp_company_id_form_user_id($member_id);
            $data_type = isset($_REQUEST['data_type']) ? $_REQUEST['data_type'] : '';
            $data_sort = isset($_REQUEST['data_sort']) ? $_REQUEST['data_sort'] : '';

            $wp_dp_query_args = array();
            if ( $data_type == 'read' ) {
                $wp_dp_query_args[] = array(
                    'key' => 'buyer_read_status',
                    'value' => 1,
                    'compare' => '='
                );
            }
            if ( $data_type == 'unread' ) {
                $wp_dp_query_args[] = array(
                    'key' => 'buyer_read_status',
                    'value' => 0,
                    'compare' => '='
                );
            }

            $args = array(
                'post_type' => 'listing_enquiries',
                'post_status' => 'publish',
                'posts_per_page' => $posts_per_page,
                'paged' => $posts_paged,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_enquiry_member',
                        'value' => $member_company_id,
                        'compare' => '=',
                    ),
                    $wp_dp_query_args
                ),
            );

            if ( $data_sort == 'asc' ) {
                $args['orderby'] = 'date';
                $args['order'] = 'ASC';
            } elseif ( $data_sort == 'desc' ) {
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
            }

            $enquiry_query = new WP_Query($args);
            $total_posts = $enquiry_query->found_posts;
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_enquires'); ?></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_member_submitted_messages'); ?></li>
            </ul>
            <div class="user-dashboard-background">
                <?php
                echo force_balance_tags($this->render_view_enquiries($enquiry_query, 'my'));
                ?>
            </div>
            <?php
            wp_reset_postdata();

            $total_pages = 1;
            if ( $total_posts > 0 && $posts_per_page > 0 && $total_posts > $posts_per_page ) {
                $total_pages = ceil($total_posts / $posts_per_page);
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $this_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'enquiries' ), $wp_dp_dashboard_link) : '';
                wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'enquiries');
            }

            wp_die();
        }

        public function wp_dp_member_received_enquiries_callback($member_id = '') {
            global $wp_dp_plugin_options;
            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';
            $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 1;
            $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';

            // Member ID.
            if ( ! isset($member_id) || $member_id == '' ) {
                $member_id = get_current_user_id();
            }
            $data_param = wp_dp_get_input('data_param', '');
            $data_type = wp_dp_get_input('data_type', '');
            $data_sort = wp_dp_get_input('data_sort', '');
            $member_company_id = wp_dp_company_id_form_user_id($member_id);
            $qry_filtr = array();

            if ( $data_type == 'read' ) {
                $qry_filtr[] = array(
                    'key' => 'seller_read_status',
                    'value' => 1,
                    'compare' => '='
                );
            } else if ( $data_type == 'unread' ) {
                $qry_filtr[] = array(
                    'key' => 'seller_read_status',
                    'value' => 0,
                    'compare' => '='
                );
            }
            if ( $data_param != '' && is_numeric($data_param) ) {
                $qry_filtr[] = array(
                    'key' => 'wp_dp_listing_id',
                    'value' => $data_param,
                    'compare' => '=',
                );
            }

            $args = array(
                'post_type' => 'listing_enquiries',
                'post_status' => 'publish',
                'posts_per_page' => $posts_per_page,
                'paged' => $posts_paged,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_member',
                        'value' => $member_company_id,
                        'compare' => '=',
                    ),
                    $qry_filtr,
                ),
            );

            if ( $data_sort == 'asc' ) {
                $args['orderby'] = 'date';
                $args['order'] = 'ASC';
            } elseif ( $data_sort == 'desc' ) {
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
            }

            $enquiry_query = new WP_Query($args);
            $total_posts = $enquiry_query->found_posts;
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_enquires'); ?></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_member_received_messages'); ?></li>
            </ul>
            <div class="user-dashboard-background">
                <?php
                echo force_balance_tags($this->render_view_enquiries($enquiry_query, 'received'));
                ?>
            </div>
            <?php
            wp_reset_postdata();

            $total_pages = 1;
            if ( $total_posts > 0 && $posts_per_page > 0 && $total_posts > $posts_per_page ) {
                $total_pages = ceil($total_posts / $posts_per_page);
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $this_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'enquiries_received' ), $wp_dp_dashboard_link) : '';
                wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'received_enquiries');
            }
            wp_die();
        }

        public function render_view_enquiries($enquiry_query = '', $type = 'my') {
            global $wp_dp_form_fields_frontend;
            $has_border = ' has-border';
            if ( $enquiry_query->have_posts() ) :
                $has_border = '';
            endif;

            $listing_id = wp_dp_get_input('data_param', '');
            $listing_title = '';
            if ( $listing_id != '' && is_numeric($listing_id) ) {
                $listing_title = get_the_title($listing_id) . ' > ';
            }
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="element-title<?php echo wp_dp_allow_special_char($has_border); ?>">
                        <h4>
                            <?php
                            if ( $type == 'my' ) {
                                $data_query = 'enquiries';
                                $messages_id = 'wp_dp_member_enquiries';
                                echo wp_dp_plugin_text_srt('wp_dp_member_submitted_messages');
                            } else {
                                $data_query = 'enquiries_received';
                                $messages_id = 'wp_dp_member_received_enquiries';
                                echo wp_dp_cs_allow_special_char($listing_title . wp_dp_plugin_text_srt('wp_dp_member_received_messages'));
                            }
                            $data_type = wp_dp_get_input('data_type', '');
                            $data_param = wp_dp_get_input('data_param', '');
                            $data_sort = wp_dp_get_input('data_sort', '');
                            $page_id_all = wp_dp_get_input('page_id_all', '');
                            ?>
                        </h4>
                        <div class="right-filters row pull-right">
                            <div class="col-lg-8 col-md-8 col-xs-8">
                                <label><?php echo wp_dp_plugin_text_srt('wp_dp_listings_sort_by'); ?>:</label>
                                <div class="input-field">
                                    <?php
                                    $options = array(
                                        'all' => wp_dp_plugin_text_srt('wp_dp_options_all'),
                                        'unread' => wp_dp_plugin_text_srt('wp_dp_member_unread_messages'),
                                    );
                                    $wp_dp_opt_array = array(
                                        'std' => $data_type,
                                        'cust_id' => 'messages_type',
                                        'cust_name' => 'messages_type',
                                        'classes' => 'chosen-select-no-single',
                                        'options' => $options,
                                        'extra_atr' => 'onchange="wp_dp_change_messages_type(this.value, \'' . $messages_id . '\', \'' . $data_query . '\', \'' . $data_param . '\', \'' . $data_sort . '\', \'' . $page_id_all . '\')"',
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                                    ?>  
                                </div>
                                <script type="text/javascript">
                                    jQuery(document).ready(function () {
                                        chosen_selectionbox();
                                    });
                                    function wp_dp_change_messages_type(val, actionString, data_query, data_param, data_sort, page_id_all) {
                                        wp_dp_show_loader('.loader-holder');
                                        if (typeof (ajaxRequest) != 'undefined') {
                                            ajaxRequest.abort();
                                        }

                                        var lang_code_param = '';
                                        if (typeof lang_code !== "undefined" && lang_code !== '') {
                                            lang_code_param = "lang=" + lang_code;
                                        }
                                        var lang = wp_dp_getParameterByName('lang');
                                        if (typeof lang !== "undefined" && lang !== '' && lang !== null) {
                                            lang_code_param = "lang=" + lang;
                                        }

                                        data_query = 'dashboard=' + data_query;
                                        if (typeof data_param !== "undefined" && data_param != '' && data_param != null) {
                                            data_query += "&data_param=" + data_param;
                                            actionString += "&data_param=" + data_param;

                                        }
                                        if (typeof data_sort !== "undefined" && data_sort != '' && data_sort != null) {
                                            data_query += "&data_sort=" + data_sort;
                                            actionString += "&data_sort=" + data_sort;
                                        }
                                        if (typeof page_id_all !== "undefined" && page_id_all != '' && page_id_all != null) {
                                            data_query += "&page_id_all=" + page_id_all;
                                            actionString += "&page_id_all=" + page_id_all;
                                        }

                                        if (history.pushState) {
                                            if (val != undefined) {
                                                if (data_query != "") {
                                                    if (typeof lang_code_param !== "undefined" && lang_code_param !== '') {
                                                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + lang_code_param + '&' + data_query + '&data_type=' + val;
                                                    } else {
                                                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + data_query + '&data_type=' + val;
                                                    }
                                                } else {
                                                    if (typeof lang_code_param !== "undefined" && lang_code_param !== '') {
                                                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + lang_code_param;
                                                    } else {
                                                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                                                    }
                                                }
                                                window.history.pushState({
                                                    path: newurl
                                                }, "", newurl);
                                            }
                                        }

                                        ajaxRequest = jQuery.ajax({
                                            type: "POST",
                                            url: wp_dp_globals.ajax_url,
                                            data: 'action=' + actionString + '&data_type=' + val,
                                            success: function (response) {
                                                wp_dp_hide_loader();
                                                jQuery('.user-holder').html(response);
                                            }
                                        });
                                    }

                                    function wp_dp_getParameterByName(name, url) {
                                        if (!url)
                                            url = window.location.href;
                                        name = name.replace(/[\[\]]/g, "\\$&");
                                        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                                                results = regex.exec(url);
                                        if (!results)
                                            return null;
                                        if (!results[2])
                                            return '';
                                        return decodeURIComponent(results[2].replace(/\+/g, " "));
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="user-orders-list">
                        <ul class="orders-list enquiries-list <?php echo ($type) == 'my' ? 'submitted' : 'received'; ?>" id="portfolio">
                            <?php if ( $enquiry_query->have_posts() ) : ?>
                                <li class="headings">
                                    <?php if ( $type == 'my' ) { ?>
                                        <div class="icon-holder"></div>
                                        <div class="orders-title ">
                                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_email_to_msg_txt_msg'); ?></span>
                                        </div>
                                        <div class="orders-type">
                                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_search_fields_date_to'); ?></span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="orders-type">
                                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_template_post_from'); ?></span>
                                        </div>
                                        <div class="orders-title">
                                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_email_to_msg_txt_msg'); ?></span>
                                        </div>
                                    <?php } ?>
                                    <div class="orders-date messages-order">
                                        <span>
                                            <?php
                                            $data_pagenum = '';
                                            if ( $page_id_all != '' ) {
                                                $data_pagenum = 'data-pagenum="' . $page_id_all . '"';
                                            }
                                            if ( $page_id_all != '' && $page_id_all != '1' ) {
                                                $data_query .= '&page_id_all=' . $page_id_all;
                                            }
                                            ?>
                                            <?php if ( $data_sort == '' || $data_sort == 'desc' ) { ?>
                                                <a class="user_dashboard_ajax asc" <?php echo ($data_pagenum); ?> data-sort="asc" data-type="<?php echo esc_html($data_type); ?>" data-param="<?php echo esc_html($data_param); ?>" id="<?php echo esc_html($messages_id); ?>" data-queryvar="dashboard=<?php echo esc_html($data_query); ?>" href="javascript:void(0);"><i class="icon-compare_arrows"></i></a>
                                            <?php } else { ?>
                                                <a class="user_dashboard_ajax desc" <?php echo ($data_pagenum); ?> data-sort="desc" data-type="<?php echo esc_html($data_type); ?>" data-param="<?php echo esc_html($data_param); ?>" id="<?php echo esc_html($messages_id); ?>" data-queryvar="dashboard=<?php echo esc_html($data_query); ?>" href="javascript:void(0);"><i class="icon-compare_arrows"></i></a>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </li>
                                <?php echo force_balance_tags($this->render_list_item_view($enquiry_query, $type)); ?>
                            <?php else: ?>
                                <li class="no-order-list-found">
                                    <?php
                                    if ( $type == 'received' ) {
                                        echo wp_dp_plugin_text_srt('wp_dp_member_enquiries_not_received_enquiry');
                                    } else {
                                        echo wp_dp_plugin_text_srt('wp_dp_member_enquiries_not_enquiry');
                                    }
                                    ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }

        public function render_list_item_view($enquiry_query, $type = 'my') {
            while ( $enquiry_query->have_posts() ) : $enquiry_query->the_post();

                $enquiry_listing_id = get_post_meta(get_the_ID(), 'wp_dp_listing_id', true);
                $buyer_read_status = get_post_meta(get_the_ID(), 'buyer_read_status', true);
                $seller_read_status = get_post_meta(get_the_ID(), 'seller_read_status', true);
                $wp_dp_user_message_title = get_post_meta(get_the_ID(), 'wp_dp_user_message_title', true);
                if ( $type == 'my' ) {

                    $member_name = get_post_meta(get_the_ID(), 'wp_dp_listing_member', true);
                    if ( $buyer_read_status == 1 ) {
                        $read_unread = 'read';
                        $read_unread_mark = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_as_read');
                    } else {
                        $read_unread_mark = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_as_unread');
                        $read_unread = 'unread';
                    }
                    $read_status = $buyer_read_status;
                } else {
                    $member_name = get_post_meta(get_the_ID(), 'wp_dp_enquiry_member', true);
                    if ( $seller_read_status == 1 ) {
                        $read_unread = 'read';
                        $read_unread_mark = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_as_read');
                    } else {
                        $read_unread_mark = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_as_unread');
                        $read_unread = 'unread';
                    }
                    $read_status = $seller_read_status;
                }
                ?>
                <li id="enuiry-<?php the_ID(); ?>" class="<?php echo esc_html($read_unread); ?>"> 
                    <div class="icon-holder">
                        <a class="change-enquiry-status" href="javascript:void(0);" data-id="<?php echo get_the_ID(); ?>" data-type="<?php echo ($type); ?>">
                            <div class="info-tooltip top-tooltip">
                                <i class="icon-star"></i>
                                <div class="info-content"><span><?php echo esc_html($read_unread_mark); ?></span></div>
                            </div>
                        </a>
                    </div>
                    <?php if ( $type == 'my' ) { ?>
                        <div class="orders-title" onclick="javascript:wp_dp_enquiry_detail('<?php the_ID(); ?>', '<?php echo esc_html($type); ?>', '<?php echo esc_html($read_status); ?>');">
                            <h6 class="order-title">
                                <a href="javascript:void(0);" onclick="javascript:wp_dp_enquiry_detail('<?php the_ID(); ?>', '<?php echo esc_html($type); ?>', '<?php echo esc_html($read_status); ?>');">
                                    <?php if ( ! empty($wp_dp_user_message_title) ) { ?>
                                        <p><?php echo esc_html($wp_dp_user_message_title); ?></p>@<?php echo get_the_title($enquiry_listing_id); ?>
                                    <?php } else { ?>
                                        <?php echo get_the_title($enquiry_listing_id); ?>
                                    <?php } ?>
                                </a>
                            </h6>
                        </div>
                        <div class="orders-type" onclick="javascript:wp_dp_enquiry_detail('<?php the_ID(); ?>', '<?php echo esc_html($type); ?>', '<?php echo esc_html($read_status); ?>');">
                            <span><a href="<?php echo get_the_permalink($member_name); ?>"><?php echo get_the_title($member_name); ?></a></span>
                        </div>
                    <?php } else { ?>
                        <div class="orders-type" onclick="javascript:wp_dp_enquiry_detail('<?php the_ID(); ?>', '<?php echo esc_html($type); ?>', '<?php echo esc_html($read_status); ?>');">
                            <span><a href="<?php echo get_the_permalink($member_name); ?>"><?php echo get_the_title($member_name); ?></a></span>
                        </div>
                        <div class="orders-title" onclick="javascript:wp_dp_enquiry_detail('<?php the_ID(); ?>', '<?php echo esc_html($type); ?>', '<?php echo esc_html($read_status); ?>');">
                            <h6 class="order-title">
                                <a href="javascript:void(0);" onclick="javascript:wp_dp_enquiry_detail('<?php the_ID(); ?>', '<?php echo esc_html($type); ?>', '<?php echo esc_html($read_status); ?>');">
                                    <?php if ( ! empty($wp_dp_user_message_title) ) { ?>
                                        <p><?php echo esc_html($wp_dp_user_message_title); ?></p>@<?php echo get_the_title($enquiry_listing_id); ?>
                                    <?php } else { ?>
                                        <?php echo get_the_title($enquiry_listing_id); ?>
                                    <?php } ?>
                                </a>
                            </h6>
                        </div>
                    <?php } ?>
                    <div class="orders-date" onclick="javascript:wp_dp_enquiry_detail('<?php the_ID(); ?>', '<?php echo esc_html($type); ?>', '<?php echo esc_html($read_status); ?>');">
                        <span>
                            <?php
                            $args = array(
                                'post_id' => get_the_ID(),
                                'status' => 'approve',
                                'orderby' => 'ID',
                                'order' => 'ASC',
                            );
                            $comments = get_comments($args);
                            $enquiry_publish_date = '';
                            foreach ( $comments as $comment ) {
                                $enquiry_publish_date = $comment->comment_date;
                            }
                            if(empty($enquiry_publish_date)){
                               $enquiry_publish_date = get_the_date('Y-m-d H:i:s', get_the_ID());
                            }
                           
                            echo wp_dp_date_custom_format($enquiry_publish_date, 'M, d Y H:i:s');
                            ?>
                        </span>
                    </div>

                </li>
                <?php
            endwhile;
        }

        public function enquiry_status_color($order_name = 'processing') {
            global $wp_dp_plugin_options;

            $orders_status = isset($wp_dp_plugin_options['orders_status']) ? $wp_dp_plugin_options['orders_status'] : '';
            $orders_color = isset($wp_dp_plugin_options['orders_color']) ? $wp_dp_plugin_options['orders_color'] : '';
            if ( is_array($orders_status) && sizeof($orders_status) > 0 ) {
                foreach ( $orders_status as $key => $lable ) {
                    if ( strtolower($lable) == strtolower($order_name) ) {
                        return $order_color = isset($orders_color[$key]) ? $orders_color[$key] : '';
                        break;
                    }
                }
            }
        }

    }

    global $orders_inquiries;
    $orders_inquiries = new Wp_dp_Member_Listing_Enquiries();
}
