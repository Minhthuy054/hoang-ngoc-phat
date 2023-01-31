<?php
/**
 * Register Post Type Inventory Type
 * @return
 *
 */
if ( ! class_exists('Wp_dp_Post_Listing_Types') ) {

    class Wp_dp_Post_Listing_Types {

        // The Constructor
        public function __construct() {
            add_action('init', array( $this, 'listing_type_register' ), 12);
            add_action('admin_menu', array( $this, 'wp_dp_remove_post_boxes' ));
            add_action('do_meta_boxes', array( $this, 'wp_dp_remove_post_boxes' ));
            add_filter('post_row_actions', array( $this, 'listing_type_remove_row_actions' ), 10, 2);
            add_action('views_edit-listing-type', array( $this, 'wp_dp_remove_views' ));
            add_filter('bulk_actions-edit-listing-type', array( $this, 'my_custom_bulk_actions' ), 10, 1);
            add_filter('handle_bulk_actions-edit-listing-type', array( $this, 'my_bulk_action_handler' ), 10, 3);

            add_action('admin_head', array( $this, 'stop_heartbeat' ), 1);
            add_action('wp_dp_plugin_db_structure_updater', array( $this, 'wp_dp_plugin_db_structure_updater_callback' ), 10);
            // column settings
            add_filter('manage_listing-type_posts_columns', array( $this, 'wp_dp_listing_type_columns_add' ));
            add_action('manage_listing-type_posts_custom_column', array( $this, 'wp_dp_listing_type_columns' ), 10, 2);
            add_action('admin_head', array( $this, 'check_post_type_and_remove_media_buttons' ));
            
            add_filter('parse_query', array( &$this, 'wp_dp_listing_types_filter' ), 11, 1);
        }

        /**
         * Start Function How to Add Title Columns
         */
        public function wp_dp_listing_type_columns_add($columns) {

            $columns['categoriez'] = wp_dp_plugin_text_srt('wp_dp_map_search_categories_txt');
            $columns['num_of_listing'] = wp_dp_plugin_text_srt('wp_dp_listing_type_column_settings_num_listing');

            $new_columns = array();
            foreach ( $columns as $key => $value ) {
                $new_columns[$key] = $value;
                if ( $key == 'cb' ) {
                    $new_columns['image_icon'] = '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_type_column_settings_image_icon') . '" class="dashicons dashicons-format-image"></i>';
                }
                $new_columns['title'] = 'Name';
            }
            return $new_columns;
        }

        function check_post_type_and_remove_media_buttons() {

            $screen = get_current_screen();
            if ( isset($screen->post_type) && $screen->post_type == 'listing-type' ) {
                add_filter('screen_options_show_screen', '__return_false');
            }

            global $current_screen;
            if ( get_post_type() == 'listing-type' ) {
                remove_action('media_buttons', 'media_buttons');
                $url_req = $_SERVER['REQUEST_URI'];
                $url_comp = (parse_url($url_req));
                $url_comp['query'];
                $post_edit_exists = false;
                if ( isset($url_comp['query']) && ! empty($url_comp['query']) ) {
                    $post_edit_exists = strpos($url_comp['query'], 'action=edit');
                }
                echo '<style type="text/css">';
                echo '.post-type-listing-type .column-image_icon { width:30px !important; overflow:hidden }';
                if ( $post_edit_exists ) {
                    echo '.page-title-action{ display:none;}';
                    echo '.wrap h1.wp-heading-inline{ display:none;}';
                    echo '#delete-action{ display:none;}';
                }
                echo '</style>';
            }
        }

        public function wp_dp_listing_type_columns($name) {
            global $post;
            $post_id = $post->ID;
            switch ( $name ) {
                default:
                    //echo do stuff here
                    break;
                case 'image_icon':
                    $screen = get_current_screen();
                    $wp_dp_listing_type_icon_image = get_post_meta($post_id, 'wp_dp_listing_type_icon_image', true);
                    $listing_type_img_icon = '';
                    if ( isset($wp_dp_listing_type_icon_image) && $wp_dp_listing_type_icon_image == 'icon' ) {
                        $listing_type_img_icon = get_post_meta($post_id, 'wp_dp_listing_type_icon', true);
                        $listing_type_img_icon = isset($listing_type_img_icon[0]) ? $listing_type_img_icon[0] : '';
                        echo '<i class="' . $listing_type_img_icon . '"></i>';
                    } else if ( isset($wp_dp_listing_type_icon_image) && $wp_dp_listing_type_icon_image == 'image' ) {
                        $listing_type_img_id = get_post_meta($post_id, 'wp_dp_listing_type_image', true);
                        $listing_type_img_src = isset($listing_type_img_id) && ! empty($listing_type_img_id) ? wp_get_attachment_image_src($listing_type_img_id) : '';
                        $listing_type_img_src = isset($listing_type_img_src[0]) ? $listing_type_img_src[0] : '';
                        echo '<img src="' . esc_url($listing_type_img_src) . '" alt=""/>';
                    } else {
                        echo '-';
                    }
                    break;
                case 'num_of_listing':
                    if ( isset($post->post_name) && ! empty($post->post_name) ) {
                        $element_filter_arr[] = array(
                            'key' => 'wp_dp_listing_type',
                            'value' => $post->post_name,
                            'compare' => '=',
                        );
                        $args_count = array(
                            'posts_per_page' => "1",
                            'post_type' => 'listings',
                            'post_status' => 'publish',
                            'meta_query' => array(
                                $element_filter_arr,
                            ),
                        );
                        $the_query = new WP_Query($args_count);
                        echo $the_query->found_posts;
                    } else {
                        echo '-';
                    }
                    break;
                case 'categoriez':
                    $wp_dp_listing_type_cats = get_post_meta($post_id, 'wp_dp_listing_type_cats', true);
                    $tag_obj_array = array();
                    if ( is_array($wp_dp_listing_type_cats) ) {
                        foreach ( $wp_dp_listing_type_cats as $tag_r ) {
                            $tag_obj = get_term_by('slug', $tag_r, 'listing-category');
                            $wp_dp_cate_str = '';
							if ( is_object($tag_obj) ) {
								$wp_dp_cate_str = '<a href="' . get_edit_term_link($tag_obj->term_id) . '">' . $tag_obj->name . '</a>';
								$tag_obj_array[$tag_obj->slug] = $wp_dp_cate_str;
                            }
                        }
                    }
                    echo implode(' ,', $tag_obj_array);
                    break;
            }
        }

        public function wp_dp_plugin_db_structure_updater_callback() {
            global $wp_dp_listing_meta;
            $post_type_updated = get_option('wp_dp_post_type_updated');

            if ( $post_type_updated != 'yes' ) {
                $listing_type_ids = get_posts(array(
                    'posts_per_page' => '-1',
                    'fields' => 'ids',
                    'post_type' => 'listing-type',
                ));
                if ( is_array($listing_type_ids) && ! empty($listing_type_ids) ) {
                    foreach ( $listing_type_ids as $listing_type_id ) {
                        $wp_dp_features_element = get_post_meta($listing_type_id, 'wp_dp_features_element', true);
                        if ( empty($wp_dp_features_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_features_element', 'on');
                        }

                        $wp_dp_tags_element = get_post_meta($listing_type_id, 'wp_dp_tags_element', true);
                        if ( empty($wp_dp_tags_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_tags_element', 'on');
                        }

                        $wp_dp_image_gallery_element = get_post_meta($listing_type_id, 'wp_dp_image_gallery_element', true);
                        if ( empty($wp_dp_image_gallery_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_image_gallery_element', 'on');
                        }

                        $wp_dp_social_share_element = get_post_meta($listing_type_id, 'wp_dp_social_share_element', true);
                        if ( empty($wp_dp_social_share_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_social_share_element', 'on');
                        }

                        $wp_dp_print_switch = get_post_meta($listing_type_id, 'wp_dp_print_switch', true);
                        if ( empty($wp_dp_print_switch) ) {
                            update_post_meta($listing_type_id, 'wp_dp_print_switch', 'on');
                        }

                        $wp_dp_claim_switch = get_post_meta($listing_type_id, 'wp_dp_claim_switch', true);
                        if ( empty($wp_dp_claim_switch) ) {
                            update_post_meta($listing_type_id, 'wp_dp_claim_switch', 'on');
                        }

                        $wp_dp_flag_switch = get_post_meta($listing_type_id, 'wp_dp_flag_switch', true);
                        if ( empty($wp_dp_flag_switch) ) {
                            update_post_meta($listing_type_id, 'wp_dp_flag_switch', 'on');
                        }

                        $wp_dp_location_element = get_post_meta($listing_type_id, 'wp_dp_location_element', true);
                        if ( empty($wp_dp_location_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_location_element', 'on');
                        }

                        $wp_dp_attachments_options_element = get_post_meta($listing_type_id, 'wp_dp_attachments_options_element', true);
                        if ( empty($wp_dp_attachments_options_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_attachments_options_element', 'on');
                        }

                        $wp_dp_video_element = get_post_meta($listing_type_id, 'wp_dp_video_element', true);
                        if ( empty($wp_dp_video_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_video_element', 'on');
                        }

                        $wp_dp_virtual_tour_element = get_post_meta($listing_type_id, 'wp_dp_virtual_tour_element', true);
                        if ( empty($wp_dp_virtual_tour_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_virtual_tour_element', 'on');
                        }

                        $wp_dp_faqs_options_element = get_post_meta($listing_type_id, 'wp_dp_faqs_options_element', true);
                        if ( empty($wp_dp_faqs_options_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_faqs_options_element', 'on');
                        }

                        $wp_dp_near_by_options_element = get_post_meta($listing_type_id, 'wp_dp_near_by_options_element', true);
                        if ( empty($wp_dp_near_by_options_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_near_by_options_element', 'on');
                        }

                        $wp_dp_yelp_places_element = get_post_meta($listing_type_id, 'wp_dp_yelp_places_element', true);
                        if ( empty($wp_dp_yelp_places_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_yelp_places_element', 'on');
                        }

                        $wp_dp_walkscores_options_element = get_post_meta($listing_type_id, 'wp_dp_walkscores_options_element', true);
                        if ( empty($wp_dp_walkscores_options_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_walkscores_options_element', 'on');
                        }

                        $wp_dp_floor_plans_options_element = get_post_meta($listing_type_id, 'wp_dp_floor_plans_options_element', true);
                        if ( empty($wp_dp_floor_plans_options_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_floor_plans_options_element', 'on');
                        }

                        $wp_dp_appartments_options_element = get_post_meta($listing_type_id, 'wp_dp_appartments_options_element', true);
                        if ( empty($wp_dp_appartments_options_element) ) {
                            update_post_meta($listing_type_id, 'wp_dp_appartments_options_element', 'on');
                        }
                    }
                }
                update_option('wp_dp_post_type_updated', 'yes');
            }
        }

        public function stop_heartbeat() {
            if ( get_post_type() == 'listing-type' ) {
                wp_deregister_script('heartbeat');
            }
        }

        /**
         * @Register Post Type
         * @return
         *
         */
        function listing_type_register() {

            global $wp_dp_plugin_static_text;

            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
                'singular_name' => wp_dp_plugin_text_srt('wp_dp_listing_type'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
                'name_admin_bar' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
                'add_new' => wp_dp_plugin_text_srt('wp_dp_add_listing_type'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_add_listing_type'),
                'new_item' => wp_dp_plugin_text_srt('wp_dp_add_listing_type'),
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_edit_listing_type'),
                'view_item' => wp_dp_plugin_text_srt('wp_dp_listing_type'),
                'all_items' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_listing_type'),
                'not_found' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
                'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
            );

            $args = array(
                'labels' => $labels,
                'description' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
                'public' => true,
                'taxonomies' => array( 'listing-category' ),
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => 'edit.php?post_type=listings',
                'menu_position' => 25,
                'query_var' => false,
                'capability_type' => 'post',
                'has_archive' => false,
                'supports' => array( 'title' ),
                'exclude_from_search' => true
            );

            register_post_type('listing-type', $args);
        }

        function wp_dp_submit_meta_box($post, $args = array()) {
            global $action, $post, $wp_dp_plugin_static_text, $wp_dp_form_fields;


            $post_type = $post->post_type;
            $post_type_object = get_post_type_object($post_type);
            $can_publish = current_user_can($post_type_object->cap->publish_posts);
            ?>
            <div class="submitbox wp-dp-submit" id="submitpost">
                <div id="minor-publishing">
                    <div style="display:none;">
                        <?php submit_button(wp_dp_plugin_text_srt('wp_dp_submit'), 'button', 'save'); ?>
                    </div>
                    <?php if ( $post_type_object->public && ! empty($post) ) : ?>
                        <div id="preview-action">
                            <?php
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
                            ?>
                            <div class="clear"></div>
                        </div>
                    <?php endif; // public post type         ?>


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
                    <div id="delete-action">
                        <?php
                        if ( current_user_can("delete_post", $post->ID) ) {
                            if ( ! EMPTY_TRASH_DAYS ) {
                                $delete_text = wp_dp_plugin_text_srt('wp_dp_delete_permanently');
                            } else {
                                $delete_text = wp_dp_plugin_text_srt('wp_dp_move_to_trash');
                            }
                            if ( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
                                ?>
                                <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo wp_dp_allow_special_char($delete_text) ?></a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div id="publishing-action">
                        <span class="spinner"></span>
                        <?php
                        if ( ! in_array($post->post_status, array( 'publish', 'future', 'private' )) || 0 == $post->ID ) {
                            if ( $can_publish ) :
                                if ( ! empty($post->post_date_gmt) && time() < strtotime($post->post_date_gmt . ' +0000') ) :
                                    $wp_dp_opt_array = array(
                                        'std' => wp_dp_plugin_text_srt('wp_dp_schedule'),
                                        'cust_id' => 'original_publish',
                                        'cust_name' => 'original_publish',
                                        'cust_type' => 'hidden',
                                        'classes' => '',
                                    );
                                    $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                    ?>
                                    <?php submit_button(esc_html('wp_dp_schedule'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' )); ?>
                                <?php else : ?>
                                    <?php
                                    $wp_dp_opt_array = array(
                                        'std' => wp_dp_plugin_text_srt('wp_dp_publish'),
                                        'cust_id' => 'original_publish',
                                        'cust_name' => 'original_publish',
                                        'cust_type' => 'hidden',
                                        'classes' => '',
                                    );
                                    $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                    ?>
                                    <?php submit_button(wp_dp_plugin_text_srt('wp_dp_publish'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' )); ?>
                                <?php
                                endif;
                            else :
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_submit_for_review'),
                                    'cust_id' => 'original_publish',
                                    'cust_name' => 'original_publish',
                                    'cust_type' => 'hidden',
                                    'classes' => '',
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                                <?php submit_button(wp_dp_plugin_text_srt('wp_dp_submit_for_review'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' )); ?>
                            <?php
                            endif;
                        } else {

                            if ( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_update'),
                                    'cust_id' => 'original_publish',
                                    'cust_name' => 'original_publish',
                                    'cust_type' => 'hidden',
                                    'classes' => '',
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_update'),
                                    'cust_id' => 'publish',
                                    'cust_name' => 'save',
                                    'cust_type' => 'submit',
                                    'classes' => 'button button-primary button-large',
                                    'extra_attr' => ' accesskey="p"',
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            } else {
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_publish'),
                                    'cust_id' => 'original_publish',
                                    'cust_name' => 'original_publish',
                                    'cust_type' => 'hidden',
                                    'classes' => '',
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_publish'),
                                    'cust_id' => 'publish',
                                    'cust_name' => 'submit',
                                    'cust_type' => 'submit',
                                    'classes' => 'button button-primary button-large',
                                    'extra_attr' => ' accesskey="p"',
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

        public function wp_dp_types_array_callback($first_option_label = '') {
            $listing_types_data = array();
            if ( $first_option_label != '' && $first_option_label != 'NULL' ) {
                $listing_types_data['all'] = $first_option_label;
            } else if ( $first_option_label != 'NULL' ) {
                $listing_types_data['all'] = wp_dp_plugin_text_srt('wp_dp_listing_type_meta_categories');
            }
            $wp_dp_listing_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => false );
            $cust_query = get_posts($wp_dp_listing_args);
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                foreach ( $cust_query as $wp_dp_listing_type ) {
                    $listing_types_data[$wp_dp_listing_type->post_name] = get_the_title($wp_dp_listing_type->ID);
                }
            }
            return $listing_types_data;
        }

        public function wp_dp_types_custom_fields_array_required_fields($fields) {
            return 'ID, wp_dp_listing_type_cus_fields'; // etc
        }

        public function wp_dp_types_custom_fields_array($listing_type) {
            $wp_dp_listing_type_cus_fields = '';
            if ( $listing_type != '' ) {

                $listing_type_post = get_posts(array( 'fields' => 'ids', 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type", 'post_status' => 'publish' ));
                $listing_type_id = isset($listing_type_post[0]) ? $listing_type_post[0] : 0;
                $wp_dp_listing_type_cus_fields = get_post_meta($listing_type_id, "wp_dp_listing_type_cus_fields", true);
            }
            return $wp_dp_listing_type_cus_fields;
        }

        public function wp_dp_all_types_by_s($s, $record = '-1') {
            $args = array(
                'posts_per_page' => $record,
                'post_type' => 'listing-type',
                'post_status' => 'publish',
                'fields' => 'ids', // only load ids 
            );
            if ( $s != '' ) {
                $args['s'] = $s;
            }
            $listing_type_loop_obj = wp_dp_get_cached_obj('wp_dp_all_types_by_s_cached_loop_obj', $args, 12, false, 'wp_query');


            return $listing_type_loop_obj;
        }

        public function wp_dp_all_categories_by_s($s, $number = '') {
            $arg = array(
                'taxonomy' => 'listing-category',
                'hide_empty' => false,
                'search' => $s,
            );
            if ( $number != '' && $number > 0 ) {
                $arg['number'] = $number;
            }
            $terms = get_terms($arg);


            return $terms;
        }

        public function listing_type_remove_row_actions($actions, $post) {

            if ( get_post_type() === 'listing-type' ) {
                unset($actions['view']);
                unset($actions['trash']);
                unset($actions['inline hide-if-no-js']);
                $actions['deletee'] = '<span class="trash"><a class="submitdelete" href="' . get_delete_post_link($post->ID, '', true) . '">' . wp_dp_plugin_text_srt('wp_dp_listing_delete') . '</a></span>';
            }

            return $actions;
        }
		public function wp_dp_remove_views($views){
            unset($views['publish']);
            unset($views['trash']);
            return $views;
		}

        public function my_custom_bulk_actions($actions) {
            unset($actions['trash']);
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

        function wp_dp_remove_post_boxes() {

            remove_meta_box('submitdiv', 'listing-type', 'side');
            remove_meta_box('mymetabox_revslider_0', 'listing-type', 'normal');
        }
        
        function wp_dp_listing_types_filter($query) {
            global $pagenow;
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listing-type' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
                $date_query = [];
                $date_query[] = array(
                    'year'  => substr($_GET['m'],0,4),
                    'month' => substr($_GET['m'],4,5),
                );
                $query->set('date_query', $date_query);
            }
        }

    }

    global $wp_dp_post_listing_types;

    $wp_dp_post_listing_types = new Wp_dp_Post_Listing_Types();
}
if ( ! function_exists('wp_dp_remove_help_tabs') ) {

    function wp_dp_remove_help_tabs() {
        $screen = get_current_screen();
        if ( $screen->post_type == 'listing-type' ) {
            add_filter('screen_options_show_screen', '__return_false');
            add_filter('bulk_actions-edit-listing-type', '__return_empty_array');
            echo '<style type="text/css">
				.post-type-listing-type .tablenav.top,
				.post-type-listing-type .tablenav.bottom,
				.post-type-listing-type #titlediv .inside,
				.post-type-listing-type #postdivrich{
					display: none;
				}
			</style>';
            echo '
		<script>
			jQuery(document).ready(function($){
				$(\'form#post\').submit(function() {
					var errorr = 0;
					$(\'.dir-res-meta-key-field\').each(function(){
						if($(this).val() == \'\'){
							errorr = 1;
							$(this).parents(\'.pb-item-container\').find(\'.pbwp-legend\').addClass(\'item-field-error\');
						}
						if($(this).parents(\'.pb-item-container\').find(\'.pbwp-legend\').hasClass(\'item-field-error\')){
							errorr = 1;
						}
					});
					
					$(\'.dir-meta-key-field\').each(function(){
						if($(this).val() == \'\') {
							errorr = 1;
							$(this).parents(\'.pb-item-container\').find(\'.pbwp-legend\').addClass(\'item-field-error\');
						}
						if($(this).parents(\'.pb-item-container\').find(\'.pbwp-legend\').hasClass(\'item-field-error\')){
							errorr = 1;
						}
					});
					
					$(\'.field-dropdown-opt-values1\').each(function(){
						var field_this = $(this);
						var val_field = $(this).find(\'input[id^="cus_field_dropdown_options_values_"]\');
						if(val_field.length === 0){
							errorr = 1;
							$(this).parents(\'.pb-item-container\').find(\'.pbwp-legend\').addClass(\'item-field-error\');
							alert(\'Please Put atleat 1 or 2 values for dropdown options.\');
						} else {
							val_field.each(function(){
								if($(this).val() == \'\'){
									errorr = 1;
									field_this.parents(\'.pb-item-container\').find(\'.pbwp-legend\').addClass(\'item-field-error\');
									alert(\'Options Values cannot be blank.\');
								}
							});
						}
					});

					if(errorr == 0){
						return true;
					}
					return false;
				});
			});
		</script>';
        }
    }

}
if ( ! function_exists('listing_type_cpt_columns') ) {

    function listing_type_cpt_columns($columns) {
        unset($columns['date']);
        return $columns;
    }

    add_filter('manage_listing-type_posts_columns', 'listing_type_cpt_columns');
}