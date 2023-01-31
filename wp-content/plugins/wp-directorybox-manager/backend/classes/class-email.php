<?php
// Direct access not allowed.
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * File Type: WP Directorybox Manager Email
 */
if ( ! class_exists('Wp_dp_Email') ) {

    class Wp_dp_Email {

        public $email_post_type_name;

        /**
         * Start construct Functions
         */
        public function __construct() {

            $this->email_post_type_name = 'emails';
            add_action('init', array( $this, 'register_post_type_callback' ));
            add_action('add_meta_boxes', array( $this, 'add_metabox_callback' ));
            add_action('wp_ajax_process_emails', array( $this, 'process_emails_callback' ), 20);
            add_action('wp_ajax_nopriv_process_emails', array( $this, 'process_emails_callback' ), 20);
            add_action('process_emails_in_background', array( $this, 'process_emails_in_background_callback' ), 20);

            add_filter('wp_dp_plugin_option_smtp_tab', array( $this, 'create_plugin_option_smtp_tab' ), 10, 1);
            add_filter('wp_dp_smtp_plugin_options', array( $this, 'create_smtp_plugin_options' ), 10, 1);
            add_action('phpmailer_init', array( $this, 'phpmailer_init_callback' ), 10, 1);
            add_action('wp_ajax_send_smtp_mail', array( $this, 'send_smtp_mail_callback' ));
            add_action('wp_dp_send_mail', array( $this, 'send_mail_callback' ), 20, 1);
            add_filter('wp_mail_from_name', array( $this, 'wp_mail_from_name_callback' ), 10, 1);
            add_action('do_meta_boxes', array( $this, 'remove_post_boxes' ));
            add_filter('post_row_actions', array( $this, 'remove_row_actions' ), 10, 2);
            add_filter('bulk_actions-edit-emails', array( $this, 'bulk_actions_callback' ));
            add_filter('manage_emails_posts_columns', array( $this, 'set_custom_edit_columns' ));
            add_action('manage_emails_posts_custom_column', array( $this, 'custom_column' ), 10, 2);
            add_filter('manage_edit-movie_sortable_columns', array( $this, 'my_movie_sortable_columns' ));

            // Remove "Add new" button from listing page and admin menu.
            add_action('admin_head', array( $this, 'disable_new_posts_capability_callback' ), 11);

            // Custom Sort Columns
            add_filter('manage_edit-emails_sortable_columns', array( $this, 'wp_dp_emails_sortable' ));
            add_filter('request', array( $this, 'wp_dp_emails_sortable_orderby' ));
            add_action('views_edit-emails', array( $this, 'wp_dp_remove_views' ));

            add_action('restrict_manage_posts', array( $this, 'wp_dp_add_email_filters' ));
            add_filter('parse_query', array( &$this, 'wp_dp_emails_filter' ), 11, 1);
            add_filter('handle_bulk_actions-edit-emails', array( $this, 'my_bulk_action_handler' ), 10, 3);
        }

        public function wp_dp_add_email_filters() {
            global $wp_dp_form_fields, $post_type;
            if ( isset($post_type) && $post_type == 'emails' ) {
                $mailer_response = isset($_GET['mailer_response']) ? $_GET['mailer_response'] : '';
                $mailer_response_options = array(
                    '' => wp_dp_plugin_text_srt('wp_dp_mailer_response'),
                    '1' => wp_dp_plugin_text_srt('wp_dp_mailer_response_sent'),
                    '0' => wp_dp_plugin_text_srt('wp_dp_mailer_response_failed'),
                );
                $wp_dp_opt_array = array(
                    'std' => $mailer_response,
                    'id' => 'mailer_response',
                    'cust_id' => 'mailer_response',
                    'cust_name' => 'mailer_response',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $mailer_response_options,
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);

                $email_status = isset($_GET['email_status']) ? $_GET['email_status'] : '';
                $email_status_options = array(
                    '' => wp_dp_plugin_text_srt('wp_dp_class_email_column_email_status'),
                    'processed' => wp_dp_plugin_text_srt('wp_dp_email_status_processed'),
                    'new' => wp_dp_plugin_text_srt('wp_dp_email_status_new'),
                );
                $wp_dp_opt_array = array(
                    'std' => $email_status,
                    'id' => 'email_status',
                    'cust_id' => 'email_status',
                    'cust_name' => 'email_status',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $email_status_options,
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
            }
        }

        public function bulk_actions_callback($actions) {
            unset($actions['trash']);
            unset($actions['edit']);
            $actions['delete'] = wp_dp_plugin_text_srt('wp_dp_listing_delete');
            return $actions;
        }

        public function my_bulk_action_handler($redirect_to, $action_name, $post_ids) {
            if ( 'delete' === $action_name ) {
                foreach ( $post_ids as $post_id ) {
                    $post = get_post($post_id);
                    get_delete_post_link($post, '', true);
                }
                $redirect_to = add_query_arg('bulk_posts_processed', count($post_ids), $redirect_to);
                return $redirect_to;
            } else {
                return $redirect_to;
            }
        }

        function wp_dp_emails_filter($query) {
            global $pagenow;
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'emails' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0 ) {
                $date_query = [ ];
                $date_query[] = array(
                    'year' => substr($_GET['m'], 0, 4),
                    'month' => substr($_GET['m'], 4, 5),
                );
                $query->set('date_query', $date_query);
            }
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'emails' && isset($_GET['mailer_response']) && $_GET['mailer_response'] != '' ) {
                $custom_filter_arr[] = array(
                    'key' => 'mailer_response',
                    'value' => $_GET['mailer_response'],
                    'compare' => '=',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'emails' && isset($_GET['email_status']) && $_GET['email_status'] != '' ) {
                $custom_filter_arr[] = array(
                    'key' => 'email_status',
                    'value' => $_GET['email_status'],
                    'compare' => '=',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'emails' ) {
                $query->set('meta_query', $custom_filter_arr);
            }
        }

        public function wp_dp_remove_views($views) {
            unset($views['all']);
            unset($views['publish']);
            unset($views['mine']);
            return $views;
        }

        public function register_post_type_callback() {
            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_class_email_name'),
                'singular_name' => wp_dp_plugin_text_srt('wp_dp_class_email_singular_name'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_class_email_menu_name'),
                'name_admin_bar' => wp_dp_plugin_text_srt('wp_dp_class_email_name_adminbar'),
                'add_new' => wp_dp_plugin_text_srt('wp_dp_class_email_addnew'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_add_new_email'),
                'new_item' => wp_dp_plugin_text_srt('wp_dp_new_email'),
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_edit_email'),
                'view_item' => wp_dp_plugin_text_srt('wp_dp_view_email'),
                'all_items' => wp_dp_plugin_text_srt('wp_dp_sent_emails'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_search_emails'),
                'parent_item_colon' => wp_dp_plugin_text_srt('wp_dp_parent_emails'),
                'not_found' => wp_dp_plugin_text_srt('wp_dp_no_emails_found'),
                'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_no_emails_found_in_trash')
            );
            $args = array(
                'labels' => $labels,
                'description' => wp_dp_plugin_text_srt('wp_dp_listing_services_description'),
                'public' => false,
                'publicly_queryable' => false,
                'show_ui' => true,
                'show_in_menu' => 'edit.php?post_type=dp-templates',
                'query_var' => false,
                'rewrite' => array( 'slug' => 'emails' ),
                'capability_type' => 'post',
                'has_archive' => false,
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array( 'title', 'editor' ),
                'capabilities' => array(
                    'create_posts' => false,
                ),
                'map_meta_cap' => true,
            );
            register_post_type($this->email_post_type_name, $args);
        }

        function set_custom_edit_columns($columns) {
            //unset($columns['title']);
            unset($columns['date']);
            $columns['sent_to'] = wp_dp_plugin_text_srt('wp_dp_class_email_column_sent_to');
            $columns['email_status'] = wp_dp_plugin_text_srt('wp_dp_class_email_column_email_status');
            $columns['email_headers'] = wp_dp_plugin_text_srt('wp_dp_class_email_column_email_headers');
            $columns['email_response'] = wp_dp_plugin_text_srt('wp_dp_mailer_response');
            $columns['datee'] = 'Date';
            return $columns;
        }

        function custom_column($column, $post_id) {
            switch ( $column ) {
                case 'sent_to' :
                    $sent_to = get_post_meta($post_id, 'email_send_to', true);
                    if ( isset($sent_to) && $sent_to != '' ) {
                        $user = get_user_by('email', $sent_to);
                        $user_id = '';
                        if ( ! empty($user) ) {
                            $user_id = $user->ID;
                        }
                        if ( $user_id !== '' ) {
                            echo '<a href="' . esc_url(get_edit_user_link($user_id)) . '">' . esc_html($sent_to) . '</a>';
                        } else {
                            echo esc_html($sent_to);
                        }
                    } else {
                        echo '-';
                    }
                    break;
                case 'email_status' :case 'email_status' :
                    $email_status = get_post_meta($post_id, 'email_status', true);
                    if ( $email_status == 'processed' ) {
                        echo wp_dp_plugin_text_srt('wp_dp_email_processed');
                    } else {
                        echo esc_html($email_status);
                    }
                    break;
                case 'email_headers' :
                    $email_headers = get_post_meta($post_id, 'email_headers', true);
                    if ( isset($email_headers[0]) ) {
                        echo ($email_headers[0]);
                    } else {
                        echo '-';
                    }
                    break;
                case 'email_response' :
                    $mailer_response = get_post_meta($post_id, 'mailer_response', true);
                    if ( isset($mailer_response[0]) && $mailer_response[0] != '' ) {

                        if ( $mailer_response[0] == 1 ) {
                            echo wp_dp_plugin_text_srt('wp_dp_email_logs_column_settings_resp_sent');
                        } else {
                            echo wp_dp_plugin_text_srt('wp_dp_email_logs_column_settings_resp_fail');
                        }
                    } else {
                        echo '-';
                    }
                    break;
                case 'datee' :
                    echo get_the_date('F j, Y', $post_id);
                    break;
            }
        }

        public function wp_dp_emails_sortable($columns) {
            $columns['sent_to'] = 'sent_to';
            $columns['email_status'] = 'email_status';
            $columns['email_response'] = 'email_response';
            return $columns;
        }

        public function wp_dp_emails_sortable_orderby($vars) {
            if ( isset($vars['orderby']) && 'sent_to' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'email_send_to',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'email_status' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'email_status',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'email_response' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'mailer_response',
                    'orderby' => 'meta_value',
                ));
            }
            return $vars;
        }

        public function remove_row_actions($actions, $post) {
            add_thickbox();
            if ( get_post_type() === 'emails' ) {
                $actions = array(
                    'content' => '<a href="#TB_inline?width=600&height=800&inlineId=email-content-popup-' . $post->ID . '" class="thickbox">' . wp_dp_plugin_text_srt('wp_dp_email_content_link_text') . '</a> <div style="display:none;" id="email-content-popup-' . $post->ID . '">'
                    . '' . get_the_content($post->ID) . ''
                    . '</div>',
                    'delete' => '<a href="' . get_delete_post_link($post->ID, '', true) . '">' . wp_dp_plugin_text_srt('wp_dp_listing_delete') . '</a>',
                );
            }

            return $actions;
        }

        /**
         * Disable capibility to create new.
         */
        public function disable_new_posts_capability_callback() {
            global $post, $current_screen;


            // Hide link on listing page.
            if ( get_post_type() == 'emails' ) {
                ?>
                <style type="text/css">
                    .wrap .page-title-action, 
                    #edit-slug-box, 
                    .submitbox .preview.button,
                    .submitbox .misc-pub-visibility,
                    .submitbox .edit-timestamp,
                    .hndle ui-sortable-handle
                    .metabox-prefs:first-child{
                        display:none;
                    }
                    .post-type-wp_dp_reviews .column-review_id { width:100px !important; overflow:hidden }
                    .post-type-wp_dp_reviews .column-helpful { width:100px !important; overflow:hidden }
                    .post-type-wp_dp_reviews .column-flag { width:100px !important; overflow:hidden }
                </style>
                <?php
            }
        }

        public function add_metabox_callback() {
            add_meta_box(
                    'email-details', wp_dp_plugin_text_srt('wp_dp_email_details'), array( $this, 'render_email_details_metabox' ), $this->email_post_type_name, 'advanced', 'default'
            );

            add_meta_box('wp_dp_meta_email_template', 'Actions', array( $this, 'wp_dp_meta_email_template' ), 'dp-templates', 'normal', 'high');
        }

        public function wp_dp_meta_email_template() {
            $this->wp_dp_submit_meta_box('dp-templates', $args = array());
        }

        public function wp_dp_submit_meta_box($post_type, $args = array()) {
            global $action, $post, $wp_dp_plugin_static_text, $wp_dp_form_fields;


            $post_type_object = get_post_type_object($post_type);
            $can_publish = current_user_can($post_type_object->cap->publish_posts);
            ?>
            <div class="submitbox wp-dp-submit" id="submitpost">
                <div id="minor-publishing">
                    <div style="display:none;">
            <?php submit_button(wp_dp_plugin_text_srt('wp_dp_submit'), 'button', 'save'); ?>
                    </div>
            <?php
            if ( $post_type_object->public && ! empty($post) ) :
                if ( 'publish' == $post->post_status ) {
                    $preview_link = esc_url(get_permalink($post->ID));
                    $preview_button = wp_dp_plugin_text_srt('wp_dp_preview');
                } else {
                    $preview_link = set_url_scheme(get_permalink($post->ID));

                    /**
                     * Filter the URI of a post preview in the post submit box.
                     *
                     * @since 2.0.5
                     * @since 4.0.0 $post parameter was added.
                     *
                     * @param string  $preview_link URI the user will be directed to for a post preview.
                     * @param WP_Post $post         Post object.
                     */
                    $preview_link = esc_url(apply_filters('preview_post_link', add_query_arg('preview', 'true', esc_url($preview_link)), $post));
                    $preview_button = wp_dp_plugin_text_srt('wp_dp_preview');
                }

            endif; // public post type        
            ?>
                </div>
                <div id="major-publishing-actions" style="border-top:0px">
                    <?php
                    /**
                     * Fires at the beginning of the publishing actions section of the Publish meta box.
                     *
                     * @since 2.7.0
                     */
                    do_action('post_submitbox_start');
                    ?>
                    <div id="publishing-action">

                        <span class="spinner"></span>
                    <?php
                    if ( ! in_array($post->post_status, array( 'publish', 'future', 'private' )) || 0 == $post->ID ) {
                        if ( $can_publish ) :
                            if ( ! empty($post->post_date_gmt) && time() < strtotime($post->post_date_gmt . ' +0000') ) :
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_schedule'),
                                    'id' => 'original_publish',
                                    'cust_name' => 'original_publish',
                                    'return' => false,
                                    'cust_type' => 'hidden',
                                    'prefix_on' => false,
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                submit_button(esc_html('wp_dp_schedule'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ));
                            else :
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_publish'),
                                    'id' => 'original_publish',
                                    'cust_name' => 'original_publish',
                                    'return' => false,
                                    'cust_type' => 'hidden',
                                    'prefix_on' => false,
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                submit_button(wp_dp_plugin_text_srt('wp_dp_publish'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ));
                            endif;
                        else :
                            $wp_dp_opt_array = array(
                                'std' => esc_html('wp_dp_submit_for_review'),
                                'id' => 'original_publish',
                                'cust_name' => 'original_publish',
                                'return' => false,
                                'cust_type' => 'hidden',
                                'prefix_on' => false,
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            submit_button(wp_dp_plugin_text_srt('wp_dp_submit_for_review'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ));
                        endif;
                    } else {
                        if ( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
                            $wp_dp_opt_array = array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_update'),
                                'id' => 'original_publish',
                                'cust_name' => 'original_publish',
                                'return' => false,
                                'cust_type' => 'hidden',
                                'prefix_on' => false,
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            $wp_dp_opt_array = array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_update'),
                                'id' => 'publish',
                                'cust_name' => 'save',
                                'classes' => 'button button-primary button-large',
                                'return' => false,
                                'cust_type' => 'submit',
                                'extra_atr' => ' accesskey="p"',
                                'prefix_on' => false,
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        } else {
                            $wp_dp_opt_array = array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_publish'),
                                'id' => 'original_publish',
                                'cust_name' => 'original_publish',
                                'return' => false,
                                'cust_type' => 'hidden',
                                'prefix_on' => false,
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            $wp_dp_opt_array = array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_publish'),
                                'id' => 'publish',
                                'cust_name' => 'publish',
                                'classes' => 'button button-primary button-large',
                                'return' => false,
                                'cust_type' => 'submit',
                                'extra_atr' => ' accesskey="p"',
                                'prefix_on' => false,
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        }
                    }
                    ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

                        <?php
                    }

                    public function render_email_details_metabox($post) {
                        if ( isset($post) ) {
                            $post_id = $post->ID;
                            $meta = array(
                                'email_send_to' => array( 'title' => wp_dp_plugin_text_srt('wp_dp_sent_to'), '' ),
                                'email_status' => array( 'title' => wp_dp_plugin_text_srt('wp_dp_email_status'), '' ),
                                'email_headers' => array( 'title' => wp_dp_plugin_text_srt('wp_dp_email_headers'), '' ),
                                'mailer_response' => array( 'title' => wp_dp_plugin_text_srt('wp_dp_mailer_response'), '' ),
                            );
                            echo '<table>';
                            foreach ( $meta as $key => $val ) {
                                echo '<tr>';
                                echo '<td>' . $val['title'] . '</td>';
                                echo '<td>';
                                $val = get_post_meta($post_id, $key, true);
                                if ( is_array($val) ) {
                                    echo implode(', ', $val);
                                } else {
                                    echo wp_dp_allow_special_char($val);
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                        }
                    }

                    public function save_email($args) {
                        // Create post object
                        $email_post = array(
                            'post_title' => $args['subject'],
                            'post_content' => $args['message'],
                            'post_status' => 'publish',
                            'post_type' => $this->email_post_type_name,
                        );

                        // Insert the post into the database.
                        $id = wp_insert_post($email_post);

                        if ( ! is_wp_error($id) ) {
                            update_post_meta($id, 'email_status', 'new');
                            update_post_meta($id, 'email_headers', $args['headers']);
                            update_post_meta($id, 'email_send_to', $args['sent_to']);
                            update_post_meta($id, 'email_type', $args['email_type']);
                            return $id;
                        } else {
                            return 0;
                        }
                    }

                    public function process_emails_callback() {

                        $post_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : 0;

                        $args = array(
                            'post_type' => $this->email_post_type_name
                        );

                        $args['meta_query'] = array(
                            array(
                                'key' => 'email_status',
                                'value' => 'new',
                                'compare' => 'LIKE',
                            ),
                        );
                        if ( $post_id != 0 ) {
                            $args['post__in'] = array( $post_id );
                        }

                        $query = new WP_Query($args);
                        if ( $query->have_posts() ) {
                            while ( $query->have_posts() ) {
                                $query->the_post();
                                $wp_dp_post_id = get_the_ID();
                                $wp_dp_subject = get_the_title();
                                $wp_dp_message = get_the_content();
                                $wp_dp_send_to = get_post_meta($wp_dp_post_id, 'email_send_to', true);
                                $wp_dp_headers = get_post_meta($wp_dp_post_id, 'email_headers', true);
                                $wp_dp_email_type = get_post_meta($wp_dp_post_id, 'email_type', true);
                                if ( ! empty($wp_dp_email_type) ) {
                                    if ( $wp_dp_email_type == 'html' ) {
                                        add_filter('wp_mail_content_type', function () {
                                            return 'text/html';
                                        });
                                    } else {
                                        add_filter('wp_mail_content_type', function () {
                                            return 'text/plain';
                                        });
                                    }
                                }
                                $email_status = get_post_meta($wp_dp_post_id, 'email_status', true);
                                if ( $email_status != 'processed' ) {
                                    $wp_dp_confirm = wp_mail($wp_dp_send_to, $wp_dp_subject, $wp_dp_message, $wp_dp_headers);
                                    update_post_meta($wp_dp_post_id, 'email_status', 'processed');
                                    update_post_meta($wp_dp_post_id, 'mailer_response', $wp_dp_confirm + "");
                                }
                            }
                            wp_reset_postdata();
                        } else {
                            echo wp_dp_plugin_text_srt('wp_dp_no_posts_found');
                        }
                        wp_die();
                    }
                    
                    /*
                     * Processing Email in background
                     */
                    
                    public function process_emails_in_background_callback(){
                        $post_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : 0;

                        $args = array(
                            'post_type' => $this->email_post_type_name
                        );

                        $args['meta_query'] = array(
                            array(
                                'key' => 'email_status',
                                'value' => 'new',
                                'compare' => 'LIKE',
                            ),
                        );
                        if ( $post_id != 0 ) {
                            $args['post__in'] = array( $post_id );
                        }

                        $query = new WP_Query($args);
                        if ( $query->have_posts() ) {
                            while ( $query->have_posts() ) {
                                $query->the_post();
                                $wp_dp_post_id = get_the_ID();
                                $wp_dp_subject = get_the_title();
                                $wp_dp_message = get_the_content();
                                $wp_dp_send_to = get_post_meta($wp_dp_post_id, 'email_send_to', true);
                                $wp_dp_headers = get_post_meta($wp_dp_post_id, 'email_headers', true);
                                $wp_dp_email_type = get_post_meta($wp_dp_post_id, 'email_type', true);
                                if ( ! empty($wp_dp_email_type) ) {
                                    if ( $wp_dp_email_type == 'html' ) {
                                        add_filter('wp_mail_content_type', function () {
                                            return 'text/html';
                                        });
                                    } else {
                                        add_filter('wp_mail_content_type', function () {
                                            return 'text/plain';
                                        });
                                    }
                                }
                                $email_status = get_post_meta($wp_dp_post_id, 'email_status', true);
                                if ( $email_status != 'processed' ) {
                                    $wp_dp_confirm = wp_mail($wp_dp_send_to, $wp_dp_subject, $wp_dp_message, $wp_dp_headers);
                                    update_post_meta($wp_dp_post_id, 'email_status', 'processed');
                                    update_post_meta($wp_dp_post_id, 'mailer_response', $wp_dp_confirm . "");
                                }
                            }
                            wp_reset_postdata();
                        }
                    }

                    /**
                      @return array Smtp plugin option fields.
                     */
                    public function create_plugin_option_smtp_tab($wp_dp_setting_options) {
                        $wp_dp_setting_options[] = array(
                            "name" => wp_dp_plugin_text_srt('wp_dp_smtp_configuration'),
                            "fontawesome" => 'icon-email',
                            "id" => "tab-smtp-configuration",
                            "std" => "",
                            "type" => "main-heading",
                            "options" => ''
                        );
                        return $wp_dp_setting_options;
                    }

                    /**
                      @return array Smtp plugin option fields.
                     */
                    public function create_smtp_plugin_options($wp_dp_setting_options) {



                        $on_off_option = array( 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'), 'no' => wp_dp_plugin_text_srt('wp_dp_listing_no') );

                        $wp_dp_setting_options[] = array(
                            "name" => wp_dp_plugin_text_srt('wp_dp_smtp_configuration'),
                            "id" => "tab-smtp-configuration",
                            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_smtp_configuration') . '"',
                            "type" => "sub-heading",
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_smtp_configuration'),
                            "id" => "tab-settings-smtp-configuration",
                            "std" => wp_dp_plugin_text_srt('wp_dp_smtp_configuration'),
                            "type" => "section",
                            "options" => ""
                        );

                        $wp_dp_setting_options[] = array( "col_heading" => wp_dp_plugin_text_srt('wp_dp_smtp_configuration'),
                            "type" => "tab-smtp",
                            "help_text" => ""
                        );
                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_smtp_enable_smtp'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_smtp_enable_smtp_hint'),
                            "id" => "use_smtp_mail",
                            "std" => "",
                            "type" => "checkbox",
                            "onchange" => "use_smtp_mail_opt(this)",
                            "options" => $on_off_option,
                        );

                        $wp_dp_setting_options[] = array(
                            "type" => "division",
                            "enable_id" => "wp_dp_use_smtp_mail",
                            "enable_val" => "on",
                            "extra_atts" => 'id="wp-dp-no-smtp-div"',
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_smtp_authentication'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_smtp_authentication_hint'),
                            "id" => "use_smtp_auth",
                            "std" => "",
                            "type" => "checkbox",
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_smtp_host_name'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_smtp_host_name_hint'),
                            "id" => "smtp_host",
                            "std" => "",
                            "classes" => "wp-dp-dev-req-field-admin",
                            'extra_attr' => 'data-visible="wp-dp-no-smtp-div"',
                            "type" => "text",
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_smtp_port'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_smtp_port_hint'),
                            "id" => "smtp_port",
                            "std" => "",
                            "type" => "text",
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_connection_prefix'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_connection_prefix_hint'),
                            "id" => "secure_connection_type",
                            "cust_name" => "mail_set_return_path",
                            "std" => "true",
                            "classes" => "chosen-select",
                            "type" => "select",
                            "options" => array( '' => 'None', 'ssl' => 'ssl', 'tls' => 'tls' ),
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_smtp_username'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_smtp_username_hint'),
                            "id" => "smtp_username",
                            "std" => "",
                            "type" => "text",
                        );
                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_smtp_password'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_smtp_password_hint'),
                            "id" => "smtp_password",
                            "std" => "",
                            "type" => "password",
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_wordwrap_length'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_wordwrap_length_hint'),
                            "id" => "wordwrap_length",
                            "std" => "",
                            "type" => "text",
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_enable_debuggin'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_enable_debuggin_hint'),
                            "id" => "smtp_debugging",
                            "std" => "",
                            "type" => "checkbox",
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_sender_email'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_sender_email_hint'),
                            "id" => "smtp_sender_email",
                            "std" => "",
                            "type" => "text",
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_sender_name'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_sender_name_hint'),
                            "id" => "sender_name",
                            "std" => "",
                            "type" => "text",
                        );

                        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_test_email'),
                            "desc" => "",
                            "hint_text" => '',
                            "label_desc" => wp_dp_plugin_text_srt('wp_dp_test_email_hint'),
                            "id" => "test_email",
                            "std" => "",
                            "type" => "text",
                            "extra_attr" => " placeholder='" . wp_dp_plugin_text_srt('wp_dp_test_email_placeholder') . "'",
                        );

                        $wp_dp_setting_options[] = array( "name" => '',
                            "desc" => "",
                            "hint_text" => '',
                            "id" => "submit_test_email",
                            "std" => wp_dp_plugin_text_srt('wp_dp_sender_send_test'),
                            "type" => "text",
                            "cust_type" => "button",
                        );

                        $wp_dp_setting_options[] = array(
                            "type" => "division_close",
                        );

                        $wp_dp_setting_options[] = array( "col_heading" => wp_dp_plugin_text_srt('wp_dp_smtp_settings'),
                            "type" => "col-right-text",
                            "help_text" => ""
                        );

                        return $wp_dp_setting_options;
                    }

                    /**
                     * @param    PHPMailer    $phpmailer    A reference to the current instance of PHP Mailer
                     */
                    public function phpmailer_init_callback($phpmailer) {
                        $options = get_option('wp_dp_plugin_options');
                        $options = apply_filters('wp_dp_translate_options', $options);
                        // Don't configure for SMTP if no host is provided.
                        if ( empty($options['wp_dp_use_smtp_mail']) || $options['wp_dp_use_smtp_mail'] != 'on' ) {
                            return;
                        }
                        $phpmailer->IsSMTP();
                        $phpmailer->Host = isset($options['wp_dp_smtp_host']) ? $options['wp_dp_smtp_host'] : 'imap.gmail.com';
                        $phpmailer->Port = isset($options['wp_dp_smtp_port']) ? $options['wp_dp_smtp_port'] : 25;
                        $phpmailer->SMTPAuth = isset($options['wp_dp_use_smtp_auth']) ? $options['wp_dp_use_smtp_auth'] : false;
                        if ( $phpmailer->SMTPAuth ) {
                            $phpmailer->Username = isset($options['wp_dp_smtp_username']) ? $options['wp_dp_smtp_username'] : 'admin';
                            $phpmailer->Password = isset($options['wp_dp_smtp_password']) ? $options['wp_dp_smtp_password'] : 'admin';
                        }
                        if ( $options['wp_dp_secure_connection_type'] != '' )
                            $phpmailer->SMTPSecure = isset($options['wp_dp_secure_connection_type']) ? $options['wp_dp_secure_connection_type'] : '';
                        if ( $options['wp_dp_smtp_sender_email'] != '' )
                            $phpmailer->SetFrom($options['wp_dp_smtp_sender_email'], $options['wp_dp_sender_name']);
                        if ( $options['wp_dp_wordwrap_length'] > 0 )
                            $phpmailer->WordWrap = isset($options['wp_dp_wordwrap_length']) ? $options['wp_dp_wordwrap_length'] : '20';
                        if ( $options['wp_dp_smtp_debugging'] == "on" && isset($_POST['action']) && $_POST['action'] == 'send_smtp_mail' )
                            $phpmailer->SMTPDebug = true;
                    }

                    public function send_smtp_mail_callback() {

                        $user = wp_get_current_user();
                        $options = get_option('wp_dp_plugin_options');
                        $options = apply_filters('wp_dp_translate_options', $options);
                        $email = $user->user_email;
                        $email = (isset($options['wp_dp_test_email']) && $options['wp_dp_test_email'] != '') ? $options['wp_dp_test_email'] : $email;
                        $subject = wp_dp_plugin_text_srt('wp_dp_test_mail');
                        $timestamp = current_time('mysql', 1);
                        $message = sprintf(wp_dp_plugin_text_srt('wp_dp_plugin_emailing_test'), 'wp-dp');
                        $message .= "\n\n";
                        $wp_dp_from_name = isset($options['wp_dp_sender_name']) ? $options['wp_dp_sender_name'] : get_bloginfo('name');
                        $wp_dp_from_email = isset($options['wp_dp_smtp_sender_email']) ? $options['wp_dp_smtp_sender_email'] : get_option('admin_email');
                        $headers = array();
                        if ( $wp_dp_from_name != '' && $wp_dp_from_email != '' ) {
                            $headers[] = "From:" . $wp_dp_from_name . ' <' . $wp_dp_from_email . '>';
                        } elseif ( $wp_dp_from_name == '' && $wp_dp_from_email != '' ) {
                            $headers[] = "From:" . $wp_dp_from_email;
                        }

                        $array = array( 'to' => $email, 'subject' => $subject, 'message' => $message, 'headers' => $headers );
                        do_action('wp_dp_send_mail', $array);

                        // Check success
                        global $phpmailer;
                        if ( $phpmailer->ErrorInfo != "" ) {
                            $error_msg = '<div class="error"><p>' . wp_dp_plugin_text_srt('wp_dp_an_error') . '</p>';
                            $error_msg .= '<blockquote style="font-weight:bold;">';
                            $error_msg .= '<p>' . $phpmailer->ErrorInfo . '</p>';
                            $error_msg .= '</p></blockquote>';
                            $error_msg .= '</div>';
                        } else {
                            $error_msg = '<div class="updated"><p>' . wp_dp_plugin_text_srt('wp_dp_test_email_sent') . '</p>';
                            $error_msg .= '<p>' . sprintf(wp_dp_plugin_text_srt('wp_dp_body_of_test_email'), $timestamp) . '</p></div>';
                        }
                        echo htmlspecialchars_decode($error_msg);
                        exit;
                    }

                    /*
                     * Send Mail through SMTP if configured.
                     * Allowed array parameters: 
                     * array('to' => $email, 'subject' => $subject, 'message' => $message, 'headers' => $headers')
                     */

                    public function send_mail_callback($args) {
                        global $wp_dp_plugin_options;
                        $wp_dp_email_logs = isset($wp_dp_plugin_options['wp_dp_email_logs']) ? $wp_dp_plugin_options['wp_dp_email_logs'] : '';

                        $wp_dp_send_to = (isset($args['to'])) ? $args['to'] : '';
                        $wp_dp_subject = (isset($args['subject'])) ? $args['subject'] : '';
                        $wp_dp_message = (isset($args['message'])) ? $args['message'] : '';
                        $wp_dp_headers = array();
                        if ( isset($args['from']) && $args['from'] != '' ) {
                            $wp_dp_headers[] = 'From: ' . $args['from'];
                        }

                        $email_type = 'plain_text';
                        if ( isset($args['email_type']) ) {
                            $email_type = $args['email_type'];
                        }

                        $wp_dp_headers = ( isset($args['headers']) ) ? $args['headers'] : $wp_dp_headers;
                        $class_obj = ( isset($args['class_obj']) ) ? $args['class_obj'] : '';

                        $post_id = $this->save_email(array(
                            'sent_to' => $wp_dp_send_to,
                            'subject' => $wp_dp_subject,
                            'message' => $wp_dp_message,
                            'headers' => $wp_dp_headers,
                            'email_type' => $email_type,
                        ));

                        if ( $post_id != 0 ) {
                            global $wp_version;
                            /*$response = wp_remote_get(admin_url('admin-ajax.php?action=process_emails&email_id=' . $post_id), array(
                                'timeout' => 5,
                                'redirection' => 5,
                                'httpversion' => '1.1',
                                'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
                                'blocking' => true,
                                'headers' => array(),
                                'cookies' => array(),
                                'body' => null,
                                'compress' => false,
                                'decompress' => true,
                                'sslverify' => true,
                                'stream' => false,
                                'filename' => null
                            ));*/
                            
                            do_action( 'process_emails_in_background' );
                            
                        }
                        if ( $class_obj != '' ) {
                            $class_obj->is_email_sent = true;
                        }

                        if ( $wp_dp_email_logs != 'on' && $post_id != '' && is_numeric($post_id) && get_post_type($post_id) == $this->email_post_type_name ) {
                            wp_delete_post($post_id, true);
                        }
                    }

                    /**
                      @return string The name from which the email is being sent.
                     */
                    public function wp_mail_from_name_callback($original_email_from) {
                        $options = get_option('wp_dp_plugin_options');
                        $options = apply_filters('wp_dp_translate_options', $options);
                        // Don't configure for SMTP if no host is provided.
                        if ( empty($options['wp_dp_use_smtp_mail']) || $options['wp_dp_use_smtp_mail'] != 'on' || $options['wp_dp_sender_name'] == '' ) {
                            return get_bloginfo('name');
                        } else {
                            return $options['wp_dp_sender_name'];
                        }
                    }

                    //remove extra boxes
                    public function remove_post_boxes() {
                        remove_meta_box('mymetabox_revslider_0', 'emails', 'normal');
                        remove_meta_box('submitdiv', 'dp-templates', 'side');
                    }

                }

                $wp_dp_email = new Wp_dp_Email();
            }

            if ( ! function_exists('wp_dp_remove_a_from_emails') ) {

                add_action('admin_footer-edit.php', 'wp_dp_remove_a_from_emails');

                function wp_dp_remove_a_from_emails() {
                    if ( get_post_type() == 'emails' ) {
                        ?>
            <script type="text/javascript">
                jQuery('table.wp-list-table a.row-title').contents().unwrap();
            </script>
            <?php
        }
    }

}
