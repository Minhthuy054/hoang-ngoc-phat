<?php
/**
 * Register Post Type Members
 * @return
 *
 */
if ( ! class_exists('Wp_dp_Post_Type_Members') ) {

    class Wp_dp_Post_Type_Members {
		public $member_profile_baseurl;
        // The Constructor
        public function __construct() {
            add_action('init', array( $this, 'members_register' ), 12);
            
            add_filter("get_user_option_screen_layout_members", array( $this, 'wp_dp_screen_layout' ));
            add_action('admin_menu', array( $this, 'wp_dp_remove_post_boxes' ));
            add_action('do_meta_boxes', array( $this, 'wp_dp_remove_post_boxes' ));
            add_action('admin_head', array( $this, 'wp_dp_members_custom_admin_head' ));
            // Custom Sort Columns
            add_filter('manage_edit-members_sortable_columns', array( $this, 'wp_dp_members_sortable' ));
            add_filter('request', array( $this, 'wp_dp_members_column_orderby' ));
            // Change Listing Featured
            add_action('wp_ajax_wp_dp_featured_member', array( $this, 'wp_dp_featured_member_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_trusted_member', array( $this, 'wp_dp_trusted_member_callback' ), 11, 1);
            // Custom Filter
            add_action('restrict_manage_posts', array( $this, 'wp_dp_admin_custom_members_filters_fields' ), 11);
            add_filter('parse_query', array( &$this, 'wp_dp_admin_custom_members_filters_query' ), 11, 1);
            
            //remove quick edit
            //add_filter('post_row_actions',array( $this, 'remove_quick_edit'),10,1);
            
            add_filter( 'views_edit-members',array( $this,  'remove_total_views') );
            
            add_filter('views_edit-members', array($this, 'wp_dp_add_analytics'));
        }
        
        public function wp_dp_add_analytics( $views ) {
            remove_filter('parse_query', array( &$this, 'wp_dp_admin_custom_members_filters_query' ), 11, 1);
            $args = array(
                'post_type' => 'members',
                'posts_per_page' => "-1",
            );
            $custom_query = new WP_Query($args);
            $total_member = 0;
            $total_active = 0;
            $total_pending = 0;
            while ( $custom_query->have_posts() ) : $custom_query->the_post();
                global $post;
                $wp_dp_user_status = get_post_meta($post->ID, 'wp_dp_user_status', true);
                if ( isset($wp_dp_user_status) && ! empty($wp_dp_user_status) ) {
                    if ( $wp_dp_user_status == 'active' ) {
                        $total_active ++;
                    } else if ( $wp_dp_user_status == 'pending' ) {
                        $total_pending ++;
                    }
                }
                $total_member ++;
            endwhile;
            wp_reset_postdata();
            echo '
            <ul class="total-wp-dp-listing row">
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_total_members') . ' </strong><em>' . $total_member . '</em><i class="icon-users"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_active_member') . ' </strong><em>' . $total_active . '</em><i class="icon-check_circle"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_pending_member') . ' </strong><em>' . $total_pending . '</em><i class="icon-back-in-time"></i></div></li>
                </ul>
            ';
            return $views;
        }
        
        function remove_total_views( $views ) {
            $remove_views = ['future','sticky','draft','pending'];
            foreach( (array) $remove_views as $view )
            {
                if( isset( $views[$view] ) )
                    unset( $views[$view] );
            }
            return $views;
        }
        
        function remove_quick_edit( $actions ){
            global $post;
            if($post->post_type == 'members')
                unset($actions['inline hide-if-no-js']);
            return $actions;
        }

        function wp_dp_members_custom_admin_head() {
            global $current_screen;
            if ( get_post_type() == 'members' ) {
                echo '<style type="text/css">';
                echo '.post-type-members .column-member_image { width:50px !important; overflow:hidden }';
                echo '.post-type-members .column-featured_member { width:100px !important; overflow:hidden }';
                echo '.post-type-members .column-trusted_member { width:100px !important; overflow:hidden }';
                echo '</style>';
            }
        }

        /**
         * @Register Post Type
         * @return
         *
         */
        public function members_register() {

            global $wp_dp_plugin_static_text;

            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_members'),
                'singular_name' => wp_dp_plugin_text_srt('wp_dp_company'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_members'),
                'name_admin_bar' => wp_dp_plugin_text_srt('wp_dp_members'),
                'add_new' => wp_dp_plugin_text_srt('wp_dp_add_company'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_add_company'),
                'new_item' => wp_dp_plugin_text_srt('wp_dp_add_company'),
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_edit_company'),
                'view_item' => wp_dp_plugin_text_srt('wp_dp_company'),
                'all_items' => wp_dp_plugin_text_srt('wp_dp_members'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_search_members'),
                'not_found' => wp_dp_plugin_text_srt('wp_dp_members'),
                'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_members'),
            );

            $args = array(
                'labels' => $labels,
                'description' => wp_dp_plugin_text_srt('wp_dp_members'),
                'public' => true,
                'exclude_from_search' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
				'show_in_menu' => 'edit.php?post_type=listings',
                'has_archive' => false,
                'query_var' => false,
                'rewrite' => true,
                'capability_type' => 'post',
                'menu_position' => 28,
                'menu_icon' => wp_dp::plugin_url() . 'assets/backend/images/members.png',
                'supports' => array( 'title' ),
            );

            register_post_type('members', $args);

			$this->member_profile_baseurl = get_option('siteurl').'/members/';
        }

        // add submit button at bottom of post 
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

        //remove extra boxes
        public function wp_dp_remove_post_boxes() {

            remove_meta_box('submitdiv', 'members', 'side');
            remove_meta_box('mymetabox_revslider_0', 'members', 'normal');
        }

        // remove submit button
        public function wp_dp_remove_help_tabs() {
            $screen = get_current_screen();
            if ( $screen->post_type == 'members' ) {
                add_filter('screen_options_show_screen', '__return_false');
                add_filter('bulk_actions-edit-members', '__return_empty_array');
                echo '<style type="text/css">
				.post-type-members .tablenav.top,
				.post-type-members .tablenav.bottom,
				.post-type-members #titlediv .inside,
				.post-type-members #postdivrich{
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
					
					$(\'.field-dropdown-opt-values\').each(function(){
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

        // set one column layout
        public function wp_dp_screen_layout($selected) {
            return 1; // Use 1 column if user hasn't selected anything in Screen Options
        }

        public function wp_dp_members_sortable($columns) {
            $columns['email'] = 'member_email';
            $columns['featured_member'] = 'featured_member';
            $columns['trusted_member'] = 'trusted_member';
            $columns['listings'] = 'listings';
            $columns['status'] = 'member_status';
            $columns['last_login'] = 'last_login';
            $columns['active_ads'] = 'active_ads';
            $columns['expire_ads'] = 'expire_ads';
            return $columns;
        }

        public function wp_dp_members_column_orderby($vars) {
            if ( isset($vars['orderby']) && 'member_email' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_email_address',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'featured_member' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_member_is_featured',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'trusted_member' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_member_is_trusted',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'listings' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_num_of_listings',
                    'orderby' => 'meta_value_num',
                ));
            }
            if ( isset($vars['orderby']) && 'member_status' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_user_status',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'last_login' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'last_login',
                    'orderby' => 'meta_value_num',
                ));
            }
            if ( isset($vars['orderby']) && ('active_ads' == $vars['orderby'] || 'expire_ads' == $vars['orderby']) ) {
                if ( 'active_ads' == $vars['orderby'] ) {
                    $key_name = 'duration';
                }

                $args = array(
                    'post_type' => 'members',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                );
                $members = get_posts($args);

                $found_ads_member_ids = array();
                if ( ! empty($members) ) {
                    $count = count($members);
                    $i = $k = 1;
                    $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
                    foreach ( $members as $member ) {
                        // query for active add
                        $custom_filter_arr = array();
                        if ( 'active_ads' == $vars['orderby'] ) {
                            $custom_filter_arr[] = array(
                                'key' => 'wp_dp_listing_status',
                                'value' => 'active',
                                'compare' => '=',
                            );
                        }
                        if ( 'expire_ads' == $vars['orderby'] ) {
                            $custom_filter_arr[] = array(
                                'key' => 'wp_dp_listing_expired',
                                'value' => current_time('timestamp', 1),
                                'compare' => '<',
                            );
                        }
                        $args = array(
                            'post_type' => 'listings',
                            'posts_per_page' => 1,
                            'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'wp_dp_listing_member',
                                    'value' => $member,
                                    'compare' => '=',
                                ),
                                $custom_filter_arr,
                            ),
                        );
                        $query = new WP_Query($args);
                        $count_lisings = $query->found_posts;
                        wp_reset_query();
                        $members_listinds[$member] = $count_lisings;
                    }
                    if ( ! empty($members_listinds) ) {
                        if ( $order == 'asc' ) {
                            asort($members_listinds);
                        } elseif ( $order == 'desc' ) {
                            arsort($members_listinds);
                        }

                        foreach ( $members_listinds as $key => $members_listind ) {
                            $found_ads_member_ids[] = $key;
                        }
                    }
                }
                if ( ! empty($found_ads_member_ids) ) {
                    $vars = array_merge($vars, array(
                        'post__in' => $found_ads_member_ids,
                        'orderby' => 'post__in',
                    ));
                }
            }
            return $vars;
        }

        public function wp_dp_featured_member_callback() {
            if ( check_admin_referer('wp-dp-featured-member') ) {
                $member_id = absint($_GET['member_id']);
                if ( 'members' === get_post_type($member_id) ) {
					
                    update_post_meta($member_id, 'wp_dp_member_is_featured', get_post_meta($member_id, 'wp_dp_member_is_featured', true) === 'on' ? 'off' : 'on' );
                }
				
            }
			
            wp_safe_redirect(wp_get_referer() ? remove_query_arg(array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer()) : admin_url('edit.php?post_type=members') );
            die();
        }

        public function wp_dp_trusted_member_callback() {
            if ( check_admin_referer('wp-dp-trusted-member') ) {
                $member_id = absint($_GET['member_id']);
                if ( 'members' === get_post_type($member_id) ) {
                    update_post_meta($member_id, 'wp_dp_member_is_trusted', get_post_meta($member_id, 'wp_dp_member_is_trusted', true) === 'on' ? 'off' : 'on' );
                }
            }
            wp_safe_redirect(wp_get_referer() ? remove_query_arg(array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer()) : admin_url('edit.php?post_type=members') );
            die();
        }

        public function wp_dp_admin_custom_members_filters_fields() {
            global $wp_dp_form_fields, $post_type;
            //only add filter to post type you want
            if ( $post_type == 'members' ) {
				$wp_dp_member_type_options = array(
                    '' => wp_dp_plugin_text_srt('wp_dp_options_all'),
                    'featured' => wp_dp_plugin_text_srt('wp_dp_member_featured'),
                    'trusted' => wp_dp_plugin_text_srt('wp_dp_member_trusted'),
                );
                $featured_trusted_member = isset($_GET['featured_trusted_member']) ? $_GET['featured_trusted_member'] : '';
                $wp_dp_opt_array = array(
                    'std' => $featured_trusted_member,
                    'id' => 'featured_trusted_member',
                    'cust_name' => 'featured_trusted_member',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $wp_dp_member_type_options,
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);

                $member_status = array(
                    '' => wp_dp_plugin_text_srt('wp_dp_top_member_status_filter'),
                    'pending' => wp_dp_plugin_text_srt('wp_dp_member_pending'),
                    'active' => wp_dp_plugin_text_srt('wp_dp_member_active'),
                    'inactive' => wp_dp_plugin_text_srt('wp_dp_member_inactive'),
                );
                $wp_dp_member_status = isset($_GET['wp_dp_member_status']) ? $_GET['wp_dp_member_status'] : '';
                $wp_dp_opt_array = array(
                    'std' => $wp_dp_member_status,
                    'id' => 'member_status',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $member_status,
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
            }
        }

        function wp_dp_admin_custom_members_filters_query($query) {
            global $pagenow;
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'members' && isset($_GET['featured_trusted_member']) && $_GET['featured_trusted_member'] != '' ) {
                $featured_trusted_member = isset($_GET['featured_trusted_member']) ? $_GET['featured_trusted_member'] : '';
                if ( $featured_trusted_member == 'featured' ) {
                    $custom_filter_arr[] = array(
                        'key' => 'wp_dp_member_is_featured',
                        'value' => 'on',
                        'compare' => '=',
                    );
                } else if ( $featured_trusted_member == 'featured' ) {
                    $custom_filter_arr[] = array(
                        'key' => 'wp_dp_member_is_trusted',
                        'value' => 'on',
                        'compare' => '=',
                    );
                }
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'members' && isset($_GET['wp_dp_member_status']) && $_GET['wp_dp_member_status'] != '' ) {
                $custom_filter_arr[] = array(
                    'key' => 'wp_dp_user_status',
                    'value' => $_GET['wp_dp_member_status'],
                    'compare' => '=',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'members' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
                $date_query = [];
                $date_query[] = array(
                    'year'  => substr($_GET['m'],0,4),
                    'month' => substr($_GET['m'],4,5),
                );
                $query->set('date_query', $date_query);
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'members' && ! empty($custom_filter_arr) ) {
                $query->set('meta_query', $custom_filter_arr);
            }
        }

    }

    global $wp_dp_post_type_members;

    $wp_dp_post_type_members = new Wp_dp_Post_Type_Members();
}
if (!function_exists('members_custom_columns')) {
    add_filter('manage_members_posts_columns', 'members_custom_columns');

    function members_custom_columns($columns) {
    unset($columns['date']);

    $columns['email'] = wp_dp_plugin_text_srt('wp_dp_member_email');
    $columns['featured_member'] = '<span data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_meta_featured_member') . '" class="dashicons dashicons-star-filled"></span>';
    $columns['trusted_member'] = '<span data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_meta_trusted_member') . '" class="dashicons dashicons-shield-alt"></span>';
    $columns['listings'] = wp_dp_plugin_text_srt('wp_dp_member_num_of_listings');
    $columns['last_login'] = wp_dp_plugin_text_srt('wp_dp_last_login_column');
    $columns['active_ads'] = wp_dp_plugin_text_srt('wp_dp_listing_php_active_ads');
    $columns['expire_ads'] = wp_dp_plugin_text_srt('wp_dp_listing_php_expire_ads');
    $columns['status'] = wp_dp_plugin_text_srt('wp_dp_status');
    
    $new_columns = array();
    foreach ( $columns as $key => $value ) {
        $new_columns[$key] = $value;
        if ( $key == 'cb' ) {
            $new_columns['member_image'] = '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_member_image') . '" class="dashicons dashicons-format-image"></i>';
        }
    }
    return $new_columns;
}
}
if (!function_exists('manage_member_custom_column_callback')) {
    add_action('manage_members_posts_custom_column', 'manage_member_custom_column_callback');
    function manage_member_custom_column_callback($column_name) {
    global $post;
    switch ( $column_name ) {
        case 'member_image':
            $profile_image_id = get_post_meta($post->ID, 'wp_dp_profile_image', true);
            $member_image_url = esc_url(wp_dp::plugin_url() . 'assets/backend/images/placeholder.png');
            if ( $profile_image_id ) {
                $image = wp_get_attachment_image_src($profile_image_id, 'thumbnail');
                if ( is_array($image) && ! empty($image) ) {
                    $member_image_url = $image[0];
                }
            }
            echo '<img class="column-member-image" src="' . esc_url($member_image_url) . '" alt="">';
            break;
        case 'email':
            $wp_dp_email_address = get_post_meta($post->ID, 'wp_dp_email_address', true);
            if ( isset($wp_dp_email_address) && $wp_dp_email_address != '' ) {
                $user = get_user_by('email', $wp_dp_email_address);
                $user_id = '';
                if ( ! empty($user) ) {
                    $user_id = $user->ID;
                }
                if ( $user_id !== '' ) {
                    echo '<a href="' . esc_url(get_edit_user_link($user_id)) . '">' . esc_html($wp_dp_email_address) . '</a>';
                } else {
                    echo esc_html($wp_dp_email_address);
                }
            } else {
                echo '-';
            }
            break;
        case 'featured_member':
            $fetured = get_post_meta($post->ID, 'wp_dp_member_is_featured', true);
            $url = wp_nonce_url(admin_url('admin-ajax.php?action=wp_dp_featured_member&member_id=' . $post->ID), 'wp-dp-featured-member');
            echo '<a href="' . esc_url($url) . '">';
            if ( $fetured == 'on' ) {
                echo '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_yes') . '" class="dashicons dashicons-star-filled"></i>';
            } else {
                echo '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_no') . '" class="dashicons dashicons-star-empty"></i>';
            }
            echo '</a>';
            break;
        case 'trusted_member':
            $trusted = get_post_meta($post->ID, 'wp_dp_member_is_trusted', true);
            $url = wp_nonce_url(admin_url('admin-ajax.php?action=wp_dp_trusted_member&member_id=' . $post->ID), 'wp-dp-trusted-member');
            echo '<a href="' . esc_url($url) . '">';
            if ( $trusted == 'on' ) {
                echo '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_yes') . '" class="dashicons dashicons-shield-alt" style="color: green; !important"></i>';
            } else {
                echo '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_no') . '" class="dashicons dashicons-shield"></i>';
            }
            echo '</a>';
            break;
        case 'listings':
            $member_num_of_listings = get_post_meta($post->ID, 'wp_dp_num_of_listings', true);
            if ( $member_num_of_listings ) {
                echo esc_html($member_num_of_listings);
            } else {
                echo '-';
            }
            break;
        case 'status':
            $wp_dp_user_status = get_post_meta($post->ID, 'wp_dp_user_status', true);
            $member_status_color = '';
            if ( $wp_dp_user_status == 'active' ) {
                $member_status_color = ' style="color: #2ecc71; font-weight:700; !important"';
            }
            if ( $wp_dp_user_status == 'inactive' ) {
                $member_status_color = ' style="color: #ff0000; font-weight:700; !important"';
            }
            if ( $wp_dp_user_status == 'pending' ) {
                $member_status_color = ' style="color: #f67a82; font-weight:700; !important"';
            }
            $user_statuses = array(
                'pending' => wp_dp_plugin_text_srt('wp_dp_member_pending'),
                'active' => wp_dp_plugin_text_srt('wp_dp_member_active'),
                'inactive' => wp_dp_plugin_text_srt('wp_dp_member_inactive'),
            );
            $wp_dp_user_status = isset($user_statuses[$wp_dp_user_status]) ? $user_statuses[$wp_dp_user_status] : $wp_dp_user_status;
            echo '<strong ' . $member_status_color . '>' . ucwords(str_replace('-', ' ', $wp_dp_user_status)) . '</strong>';
            break;
        case 'last_login':
            $last_login = get_post_meta($post->ID, 'last_login', true);
            if ( $last_login && $last_login != '' ) {
                $date_format = get_option('date_format');
                //$output = human_time_diff($last_login, current_time('timestamp', 1)) . ' ' . wp_dp_plugin_text_srt('wp_dp_func_ago');
                $output = isset($last_login) && $last_login != '' ? date_i18n($date_format, ($last_login)) : '';
            } else {
                $output = '<span> ' . wp_dp_plugin_text_srt('wp_dp_last_login_never') . '</span>';
            }
            echo force_balance_tags($output);
            break;
        case 'active_ads':
            // query for active add
            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_member',
                        'value' => $post->ID,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'active',
                        'compare' => '=',
                    ),
                ),
            );
            $query = new WP_Query($args);
            $count_lisings = $query->found_posts;
            echo esc_html($count_lisings);
            break;
        case 'expire_ads':
            // query for active add
            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_member',
                        'value' => $post->ID,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_expired',
                        'value' => current_time('timestamp', 1),
                        'compare' => '<',
                    ),
                ),
            );
            $query = new WP_Query($args);
            $count_lisings = $query->found_posts;
            echo esc_html($count_lisings);
            break;
        case 'deatail':

            // query for active add
            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_username',
                        'value' => $post->ID,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'active',
                        'compare' => '=',
                    ),
                ),
            );
            $query = new WP_Query($args);
            $count_lisings = $query->found_posts;

            // query for expired add
            $args_expire = array(
                'post_type' => 'listings',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_username',
                        'value' => $post->ID,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_expired',
                        'value' => current_time('timestamp', 1),
                        'compare' => '<',
                    ),
                ),
            );
            $query_expire = new WP_Query($args_expire);
            $expire_adds = $query_expire->found_posts;
            $output = '<ul>
		    <li>' . wp_dp_plugin_text_srt('wp_dp_profile_views') . ' <span></span></li>';
            $output .= ' <li>' . wp_dp_plugin_text_srt('wp_dp_active_ads') . '<span>' . $count_lisings . '</span></li>
				<li>' . wp_dp_plugin_text_srt('wp_dp_expire_ads') . '<span>' . $expire_adds . '</span> </li>
		</ul>
	    ';
            echo force_balance_tags($output);
            break;
    }
}
}