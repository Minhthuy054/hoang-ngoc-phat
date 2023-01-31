<?php
/**
 * @Add Meta Box For Listing Types
 * @return
 *
 */
if ( ! class_exists('Wp_dp_Listing_Type_Meta') ) {

    class Wp_dp_Listing_Type_Meta {

        public function __construct() {
            add_action('wp_ajax_add_feature_to_list', array( $this, 'add_feature_to_list' ));
            add_action('wp_ajax_add_category_to_list', array( $this, 'add_category_to_list' ));
            add_action('add_meta_boxes', array( $this, 'wp_dp_meta_listing_type_add' ));
            add_action('save_post', array( $this, 'wp_dp_save_post_categories' ), 12);
            add_action('wp_ajax_wp_dp_ft_iconpicker', array( $this, 'wp_dp_ft_icon' ));
            add_action('wp_ajax_wp_dp_get_tags_list', array( $this, 'wp_dp_get_tags_list' ));
            add_action('wp_ajax_wp_dp_get_cats_list', array( $this, 'wp_dp_get_cats_list' ));
            add_filter("get_user_option_screen_layout_listing-type", array( $this, 'listing_type_screen_layout' ));
        }

        public function listing_type_screen_layout($selected) {
            return 1; // Use 1 column if user hasn't selected anything in Screen Options
        }

        function wp_dp_meta_listing_type_add() {
            add_meta_box('wp_dp_meta_listing_type', esc_html(wp_dp_plugin_text_srt('wp_dp_listing_type_options')), array( $this, 'wp_dp_meta_listing_type' ), 'listing-type', 'normal', 'high');
        }

        function wp_dp_meta_listing_type($post) {
            global $post, $wp_dp_html_fields, $wp_dp_post_listing_types, $wp_dp_plugin_static_text;
            ?>		
            <div class="page-wrap page-opts left" style="overflow:hidden; position:relative;">
                <div class="option-sec" style="margin-bottom:0;">
                    <div class="opt-conts">
                        <div class="elementhidden">
                            <nav class="admin-navigtion">
                                <ul id="cs-options-tab">
                                    <li><a href="javascript:void(0);" name="#tab-listing_settings"><i class="icon-build"></i><?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_general_settings'); ?></a></li>
                                    <li><a href="javascript:void(0);" name="#tab-listing_types-settings-custom-fields"><i class="icon-support"></i><?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_custom_fields'); ?></a></li>
                                    <li><a href="javascript:void(0);" name="#tab-listing_types-settings-features"><i class="icon-featured_play_list"></i><?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_features'); ?></a></li>
                                    <li><a href="javascript:void(0);" name="#tab-listing_types-settings-page-elements"><i class="icon-cogs"></i><?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_required_elements'); ?></a></li>
                                    <?php do_action('listing_type_options_sidebar_tab'); ?>
                                </ul>
                            </nav>
                            <div id="tabbed-content" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')) ?>">
                                <div id="tab-listing_settings" class="wp_dp_tab_block" data-title="<?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_general_settings'); ?>">
                                    <?php
                                    $this->listing_type_settings_tab();
                                    $this->listing_type_price_option();
                                    $this->wp_dp_post_listing_type_categories();
                                    $this->wp_dp_post_listing_type_tags();
                                    ?>
                                </div>
                                <div id="tab-listing_types-settings-custom-fields" class="wp_dp_tab_block" data-title="<?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_custom_fields'); ?>">
                                    <?php $this->wp_dp_post_listing_type_fields(); ?>
                                </div>
                                <div id="tab-listing_types-settings-features" class="wp_dp_tab_block" data-title="<?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_features'); ?>">
                                    <?php $this->wp_dp_post_listing_type_features(); ?>
                                </div>

                                <div id="tab-listing_types-settings-page-elements" class="wp_dp_tab_block" data-title="<?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_required_elements'); ?>">
                                    <?php $this->wp_dp_post_page_elements_setting(); ?>
                                </div>
                                <?php do_action('listing_type_options_tab_container'); ?>
                            </div>
                            <?php $wp_dp_post_listing_types->wp_dp_submit_meta_box('listing-type', $args = array()); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <?php
        }

        function get_attached_cats($type = '', $meta_key = '') {
            global $post;

            $wp_dp_category_array = array();
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => "$type",
                'post_status' => array( 'publish', 'pending', 'draft' ),
                'post__not_in' => array( $post->ID )
            );

            $custom_query = new WP_Query($args);
            if ( $custom_query->have_posts() <> "" ) {

                while ( $custom_query->have_posts() ): $custom_query->the_post();
                    $wp_dp_aut_categories = get_post_meta(get_the_ID(), "$meta_key", true);
                    if ( is_array($wp_dp_aut_categories) ) {
                        $wp_dp_category_array = array_merge($wp_dp_category_array, $wp_dp_aut_categories);
                    }
                endwhile;
            }
            wp_reset_postdata();

            return is_array($wp_dp_category_array) ? array_unique($wp_dp_category_array) : $wp_dp_category_array;
        }

        /**
         * @Inventory Type Custom Fileds Function
         * @return
         */
        function wp_dp_post_listing_type_fields() {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_listing_type_fields;

            $wp_dp_listing_type_fields->custom_fields();
        }

        /**
         * @Inventory Type Features Function
         * @return
         */
        function wp_dp_post_listing_type_features() {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields;

            $this->wp_dp_features_items($post);
        }

        function wp_dp_post_listing_type_tags() {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields;

            $this->wp_dp_tags_items();
        }

        function wp_dp_post_listing_type_categories() {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields;

            $wp_dp_listing_type_tags = get_post_meta($post->ID, 'wp_dp_listing_type_cats', true);
            $tag_obj_array = array();
            if ( is_array($wp_dp_listing_type_tags) && sizeof($wp_dp_listing_type_tags) > 0 ) {
                foreach ( $wp_dp_listing_type_tags as $tag_r ) {
                    $tag_obj = get_term_by('slug', $tag_r, 'listing-category');
                    if ( is_object($tag_obj) ) {
                        $tag_obj_array[$tag_obj->slug] = $tag_obj->name;
                    }
                }
            }

            $wp_dp_html_fields->wp_dp_heading_render(array( 'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_categories') ));
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_select_cats'),
                'desc' => '',
                'hint_text' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_select_cats_hint'),
                'echo' => true,
                'multi' => true,
                'desc' => sprintf('<a href="' . admin_url('edit-tags.php?taxonomy=listing-category&post_type=listings', wp_dp_server_protocol()) . '">' . wp_dp_plugin_text_srt('wp_dp_add_new_cats_link') . '</a>'),
                'field_params' => array(
                    'std' => '',
                    'id' => 'listing_type_cats',
                    'classes' => 'chosen-select-no-single chosen-select',
                    'options' => $tag_obj_array,
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
            wp_enqueue_script('chosen-ajaxify');
            echo '
			<script>
			jQuery(window).load(function(){
				chosen_ajaxify("wp_dp_listing_type_cats", "' . esc_url(admin_url('admin-ajax.php')) . '", "wp_dp_get_cats_list");
			});
			</script>';
        }

        public function wp_dp_get_cats_list() {
            $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
            $wp_dp_tags_array = get_terms('listing-category', array(
                'hide_empty' => false,
                'parent' => 0,
            ));

            $listing_types_cats = array();
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'listing-type',
                'post_status' => 'publish',
                'fields' => 'ids',
                'meta_query' => array(
                    array(
                        'key' => 'wp_dp_listing_type_cats',
                        'value' => '',
                        'compare' => '!=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $listing_types_loop = $custom_query->posts;

            $listing_types_cats_arr = array();
            if ( is_array($listing_types_loop) ) {

                foreach ( $listing_types_loop as $listing_type_id ) {
                    $listing_type_cats = get_post_meta($listing_type_id, 'wp_dp_listing_type_cats', true);
                    if ( is_array($listing_type_cats) ) {
                        foreach ( $listing_type_cats as $listing_type_cat_in ) {
                            $listing_types_cats_arr[] = $listing_type_cat_in;
                        }
                    }
                }
            }

            $wp_dp_tags_list = array();
            if ( is_array($wp_dp_tags_array) && sizeof($wp_dp_tags_array) > 0 ) {
                foreach ( $wp_dp_tags_array as $dir_tag ) {
                    if ( ! in_array($dir_tag->slug, $listing_types_cats_arr) ) {
                        $wp_dp_tags_list[] = array( 'value' => $dir_tag->slug, 'caption' => $dir_tag->name );
                    }
                }
            }
            echo json_encode($wp_dp_tags_list);
            die;
        }

        public function features_save($post_id) {
            if ( isset($_POST['wp_dp_features_array']) && is_array($_POST['wp_dp_features_array']) ) {
                $feat_array = array();
                $feat_counter = 0;
                foreach ( $_POST['wp_dp_features_array'] as $feat ) {
                    $feat_name = isset($_POST['wp_dp_feature_name_array'][$feat_counter]) ? $_POST['wp_dp_feature_name_array'][$feat_counter] : '';
                    $feat_array[$feat] = array( 'key' => 'feature_' . $feat, 'name' => $feat_name, 'icon' => $_POST['wp_dp_feature_icon_array'][$feat_counter] );
                    $feat_counter ++;
                }
                update_post_meta($post_id, 'wp_dp_listing_type_features', $feat_array);
            }
        }

        public function tags_save($post_id) {
            if ( isset($_POST['wp_dp_listing_type_tags']) ) {
                update_post_meta($post_id, 'wp_dp_listing_type_tags', $_POST['wp_dp_listing_type_tags']);
            } else {
                update_post_meta($post_id, 'wp_dp_listing_type_tags', '');
            }
        }

        public function categories_save($post_id) {
            if ( isset($_POST['wp_dp_listing_type_cats']) ) {
                update_post_meta($post_id, 'wp_dp_listing_type_cats', $_POST['wp_dp_listing_type_cats']);
            } else {
                update_post_meta($post_id, 'wp_dp_listing_type_cats', '');
            }
        }

        public function wp_dp_features_items($post) {
            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;
            $wp_dp_get_features = get_post_meta($post->ID, 'wp_dp_listing_type_features', true);
            $ratings = array();
            $post_id = $post->ID;
            $featured_lables = get_post_meta($post_id, 'feature_lables', true);
            $wp_dp_feature_icon = get_post_meta($post_id, 'wp_dp_feature_icon', true);
            $wp_dp_feature_icon_group = get_post_meta($post_id, 'wp_dp_feature_icon_group', true);
            $wp_dp_enable_not_selected = get_post_meta($post_id, 'wp_dp_enable_not_selected', true);
            ?>
            <div id="tab-features_settings">
                <?php
                $post_meta = get_post_meta(get_the_id());
                $features_data = array();
                if ( isset($post_meta['wp_dp_listing_type_features']) && isset($post_meta['wp_dp_listing_type_features'][0]) ) {
                    $features_data = json_decode($post_meta['wp_dp_listing_type_features'][0], true);
                }
                $featured_lables    = ( isset( $featured_lables ) && is_array($featured_lables ) )? $featured_lables : array();
                if ( count($featured_lables) > 0 ) {
                    $wp_dp_opt_array = array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_show_all_feature_item'),
                        'desc' => '',
                        'hint_text' => wp_dp_plugin_text_srt('wp_dp_show_all_feature_item_desc'),
                        'echo' => true,
                        'field_params' => array(
                            'std' => $wp_dp_enable_not_selected,
                            'id' => 'enable_not_selected',
                            'return' => true,
                        ),
                    );
                    $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
                }

                $icon_rand_id = rand(10000000, 99999999);
                ?>


                <div class="wp-dp-list-wrap wp-dp-features-list-wrap">
                    <ul class="wp-dp-list-layout">
                        <li class="wp-dp-list-label">
                            <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label></label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_options_feature_icon'); ?></label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_options_feature_label'); ?> </label>
                                </div>
                            </div>
                        </li>


                        <?php
                        $counter = 0;
                        if ( is_array($featured_lables) && sizeof($featured_lables) > 0 ) {

                            foreach ( $featured_lables as $key => $lable ) {
                                $icon = isset($wp_dp_feature_icon[$key]) ? $wp_dp_feature_icon[$key] : '';
                                $icon_group = isset($wp_dp_feature_icon_group[$key]) ? $wp_dp_feature_icon_group[$key] : 'default';
                                ?>
                                <li class="wp-dp-list-item">
                                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <?php echo apply_filters('cs_icons_fields', $icon, 'feature_icon' . $icon_rand_id . $counter, 'wp_dp_feature_icon', $icon_group); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => isset($lable) ? esc_html($lable) : '',
                                                    'cust_name' => 'feature_label[]',
                                                    'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_type_features_label') . '"',
                                                    'classes' => 'review_label input-field',
                                                );
                                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a>
                                </li>
                                <?php
                                $counter ++;
                            }
                        }
                        ?>
                    </ul>        
                    <ul class="wp-dp-list-button-ul">
                        <li class="wp-dp-list-button">
                            <div class="input-element">
                                <a href="javascript:void(0);" id="click-more" class="wp-dp-add-more cntrl-add-new-row" onclick="duplicate()"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_feature_add_row'); ?></a>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

            <script type="text/javascript">
                jQuery(document).ready(function () {
                    var table_class = ".wp-dp-features-list-wrap .wp-dp-list-layout";
                    jQuery(table_class).sortable({
                        cancel: "input, .wp-dp-list-label"
                    });
                });	// Function for duplicate <tr> for add features.
                var counter_val = 1;
                function duplicate() {
                    counter_val;

                    $(".wp-dp-features-list-wrap .wp-dp-list-layout").append('<li class="wp-dp-list-item"><div class="col-lg-1 col-md-1 col-sm-6 col-xs-12"><div class="input-element"><div class="input-holder"><span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span></div></div></div><div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><div class="input-element"><div class="input-holder" id="icon-' + counter_val + '<?php echo absint($icon_rand_id); ?>"></div></div></div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><div class="input-element"><div class="input-holder"><input type="text" placeholder="<?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_features_label'); ?>" class="review_label input-field" name="feature_label[]" value=""></div></div></div><a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a></li>');
                    wp_dp_ft_icon_feature(counter_val + '<?php echo absint($icon_rand_id); ?>');
                    counter_val++;
                }
                jQuery(document).on('click', '.cntrl-delete-rows', function () {
                    delete_row_top(this);
                    return false;
                });
                function delete_row_top(delete_link) {
                    $(delete_link).parent().parent().remove();
                }
            </script>
            <?php
        }

        public function wp_dp_tags_items() {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;


            $wp_dp_listing_type_tags = get_post_meta($post->ID, 'wp_dp_listing_type_tags', true);
            $tag_obj_array = array();
            if ( is_array($wp_dp_listing_type_tags) && sizeof($wp_dp_listing_type_tags) > 0 ) {
                foreach ( $wp_dp_listing_type_tags as $tag_r ) {
                    $tag_obj = get_term_by('slug', $tag_r, 'listing-tag');
                    if ( is_object($tag_obj) ) {
                        $tag_obj_array[$tag_obj->slug] = $tag_obj->name;
                    }
                }
            }
            $wp_dp_html_fields->wp_dp_heading_render(array( 'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_suggested_tags') ));
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_select_suggested_tags'),
                'desc' => '',
                'hint_text' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_select_suggested_tags_desc'),
                'echo' => true,
                'multi' => true,
                'desc' => sprintf('<a href="%s">' . wp_dp_plugin_text_srt('wp_dp_add_new_tag_link'). '</a>', admin_url('edit-tags.php?taxonomy=listing-tag&post_type=listings', wp_dp_server_protocol())),
                'field_params' => array(
                    'std' => '',
                    'id' => 'listing_type_tags',
                    'classes' => 'chosen-select-no-single chosen-select',
                    'options' => $tag_obj_array,
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            echo '
			<script>
			jQuery(window).load(function(){
				chosen_ajaxify("wp_dp_listing_type_tags", "' . esc_url(admin_url('admin-ajax.php')) . '", "wp_dp_get_tags_list");
			});
			</script>';
        }

        public function wp_dp_get_tags_list() {
            $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
            $wp_dp_tags_array = get_terms('listing-tag', array(
                'hide_empty' => false,
            ));
            $wp_dp_tags_list = array();
            if ( is_array($wp_dp_tags_array) && sizeof($wp_dp_tags_array) > 0 ) {
                foreach ( $wp_dp_tags_array as $dir_tag ) {
                    $wp_dp_tags_list[] = array( 'value' => $dir_tag->slug, 'caption' => $dir_tag->name );
                }
            }
            echo json_encode($wp_dp_tags_list);
            die;
        }

        public function wp_dp_categories_items() {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;
            $post_meta = get_post_meta(get_the_id());

            $wp_dp_get_categories = get_the_terms(get_the_id(), 'listing-category');


            $html = '
            <script>
                jQuery(document).ready(function($) {
                    $("#total_categories").sortable({
                        cancel : \'td div.table-form-elem
                    });
                });
            </script>
              <ul class="form-elements">
                  <li class="to-button"><a href="javascript:wp_dp_createpop(\'add_category_title\',\'filter\')" class="button">' . wp_dp_plugin_text_srt('wp_dp_add_category') . '</a> </li>
               </ul>
              <div class="cs-service-list-table">
              <table class="to-table" border="0" cellspacing="0">
                    <thead>
                      <tr>
                        <th style="width:60%;">' . wp_dp_plugin_text_srt('wp_dp_title') . '</th>
                        <th style="width:100%;">' . wp_dp_plugin_text_srt('wp_dp_icon') . '</th>
                        <th style="width:20%;" class="right">' . wp_dp_plugin_text_srt('wp_dp_actions') . '</th>
                      </tr>
                    </thead>
                    <tbody id="total_categories">';
            if ( is_array($wp_dp_get_categories) && sizeof($wp_dp_get_categories) > 0 ) {

                foreach ( $wp_dp_get_categories as $categories ) {
                    $category_icon = get_term_meta($categories->term_id, 'wp_dp_listing_taxonomy_icon', true);
                    $wp_dp_categories_array = array(
                        'counter_category' => $categories->term_id,
                        'category_id' => $categories->term_id,
                        'wp_dp_category_name' => $categories->name,
                        'wp_dp_listing_taxonomy_icons' => $category_icon
                    );

                    $html .= $this->add_category_to_list($wp_dp_categories_array);
                    $category_icon = '';
                }
            }

            $html .= '
                </tbody>
            </table>

            </div>
            <div id="add_category_title" style="display: none;">
                  <div class="cs-heading-area">
                    <h5><i class="icon-plus-circle"></i> ' . wp_dp_plugin_text_srt('wp_dp_listing_categories') . '</h5>
                    <span class="cs-btnclose" onClick="javascript:wp_dp_removeoverlay(\'add_category_title\',\'append\')"> <i class="icon-times"></i></span> 	
                  </div>';



            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_name'),
                'desc' => '',
                'hint_text' => '',
                'echo' => false,
                'field_params' => array(
                    'std' => '',
                    'cust_id' => 'wp_dp_category_name',
                    'cust_name' => 'wp_dp_category_name[]',
                    'return' => true,
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
            $terms = get_terms(array(
                'taxonomy' => 'listing-category',
                'hide_empty' => false,
            ));

            $cats_parents = array();
            $cats_parents[''] = wp_dp_plugin_text_srt('wp_dp_listing_type_no_parent');
            foreach ( $terms as $term ) {

                $cats_parents[$term->term_id] = $term->name;
            }
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_parent'),
                'desc' => '',
                'hint_text' => '',
                'field_params' => array(
                    'std' => '',
                    'cust_id' => 'wp_dp_category_parent',
                    'cust_name' => 'wp_dp_category_parent[]',
                    'classes' => 'dropdown chosen-select',
                    'options' => $cats_parents,
                    'return' => true,
                ),
            );


            $html .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            $html .= '<div class="form-elements"><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label>' . wp_dp_plugin_text_srt('wp_dp_icon') . '</label></div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $html .= wp_dp_iconlist_plugin_options("", "listing_type_icons", "wp_dp_listing_taxonomy_icons");
            $html .= '</div></div>';
            $html .= '
                <ul class="form-elements noborder">
                  <li class="to-label"></li>
                  <li class="to-field">
                        <input type="button" value="' . wp_dp_plugin_text_srt('wp_dp_add_category') . '" onclick="add_listing_category(\'' . esc_js(admin_url('admin-ajax.php')) . '\')" />
                        <div class="category-loader"></div>
                  </li>
                </ul>
          </div>';

            echo force_balance_tags($html, true);
        }

        public function add_feature_to_list($wp_dp_atts = array()) {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;
            $wp_dp_defaults = array(
                'counter_feature' => '',
                'feature_id' => '',
                'wp_dp_feature_name' => '',
                'wp_dp_feature_icon' => '',
                'wp_dp_feature_icon_group' => 'default',
            );
            extract(shortcode_atts($wp_dp_defaults, $wp_dp_atts));

            foreach ( $_POST as $keys => $values ) {
                $$keys = $values;
            }

            if ( isset($_POST['wp_dp_feature_name']) && $_POST['wp_dp_feature_name'] <> '' ) {
                $wp_dp_feature_name = $_POST['wp_dp_feature_name'];
            }

            if ( isset($_POST['wp_dp_feature_icon']) && $_POST['wp_dp_feature_icon'] <> '' ) {
                $wp_dp_feature_icon = $_POST['wp_dp_feature_icon'];
            }


            if ( $feature_id == '' && $counter_feature == '' ) {
                $counter_feature = $feature_id = rand(1000000000, 9999999999);
            }

            $html = '
            <tr class="parentdelete" id="edit_track' . absint($counter_feature) . '">
              <td id="subject-title' . absint($counter_feature) . '" style="width:100%;">' . esc_attr($wp_dp_feature_name) . '</td>
              <td id="subject-title' . absint($counter_feature) . '" style="width:100%;"><i class="' . esc_attr($wp_dp_feature_icon) . '"></i></td>

              <td class="centr" style="width:20%;"><a href="javascript:wp_dp_createpop(\'edit_track_form' . absint($counter_feature) . '\',\'filter\')" class="actions edit">&nbsp;</a> <a href="#" class="delete-it btndeleteit actions delete">&nbsp;</a></td>
              <td style="width:0"><div id="edit_track_form' . esc_attr($counter_feature) . '" style="display: none;" class="table-form-elem">
                <input type="hidden" name="wp_dp_features_array[]" value="' . absint($feature_id) . '" />
                  <div class="cs-heading-area">
                        <h5 style="text-align: left;">' . wp_dp_plugin_text_srt('wp_dp_listing_features') . '</h5>
                        <span onclick="javascript:wp_dp_removeoverlay(\'edit_track_form' . esc_js($counter_feature) . '\',\'append\')" class="cs-btnclose"> <i class="icon-times"></i></span>
                        <div class="clear"></div>
                  </div>';

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_title'),
                'desc' => '',
                'hint_text' => '',
                'echo' => false,
                'field_params' => array(
                    'std' => $wp_dp_feature_name,
                    'id' => 'feature_name',
                    'return' => true,
                    'array' => true,
                    'extra_atr' => 'onchange="change_feature_value(\'subject-title' . $counter_feature . '\',this.value);"',
                    'force_std' => true,
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $html .= '<div class="form-elements"><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label>' . wp_dp_plugin_text_srt('wp_dp_icon') . '</label></div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';

            $html .= apply_filters('cs_icons_fields', $wp_dp_feature_icon, 'feature_icon' . $counter_feature, 'wp_dp_feature_icon_array');

            $html .= '</div></div>';

            $html .= '
                    <ul class="form-elements noborder">
                        <li class="to-label">
                          <label></label>
                        </li>
                        <li class="to-field">
                          <input type="button" value="' . wp_dp_plugin_text_srt('wp_dp_update_feature') . '" onclick="wp_dp_removeoverlay(\'edit_track_form' . esc_js($counter_feature) . '\',\'append\')" />
                        </li>
                    </ul>
                  </div>
                </td>
            </tr>';

            if ( isset($_POST['wp_dp_feature_name']) ) {
                echo force_balance_tags($html);
            } else {
                return $html;
            }

            if ( isset($_POST['wp_dp_feature_name']) ) {
                die();
            }
        }

        public function wp_dp_ft_icon($value = '', $id = '', $name = '') {//begin function
            if ( $value == '' && $id == '' && $name == '' ) {
                $id = rand(10000000, 99999999);
                $name = 'wp_dp_feature_icon';
            }
            $html = "
			<script>
			jQuery(document).ready(function ($) {
				var this_icons;
				var rand_num = " . $id . ";
				var e9_element = $('#e9_element_' + rand_num).fontIconPicker({
					theme: 'fip-bootstrap'
				});
				icons_load_call.always(function () {
					this_icons = loaded_icons;
					// Get the class prefix
					var classPrefix = this_icons.preferences.fontPref.prefix,
							icomoon_json_icons = [],
							icomoon_json_search = [];
					$.each(this_icons.icons, function (i, v) {
						icomoon_json_icons.push(classPrefix + v.listings.name);
						if (v.icon && v.icon.tags && v.icon.tags.length) {
							icomoon_json_search.push(v.listings.name + ' ' + v.icon.tags.join(' '));
						} else {
							icomoon_json_search.push(v.listings.name);
						}
					});
					// Set new fonts on fontIconPicker
					e9_element.setIcons(icomoon_json_icons, icomoon_json_search);
					// Show success message and disable
					$('#e9_buttons_' + rand_num + ' button').removeClass('btn-primary').addClass('btn-success').text('Successfully loaded icons').prop('disabled', true);
				})
				.fail(function () {
					// Show error message and enable
					$('#e9_buttons_' + rand_num + ' button').removeClass('btn-primary').addClass('btn-danger').text('Error: Try Again?').prop('disabled', false);
				});
			});
			</script>";

            $html .= '
			<input type="text" id="e9_element_' . $id . '" name="' . $name . '[]" value="' . $value . '">
			<span id="e9_buttons_' . $id . '" style="display:none">\
				<button autocomplete="off" type="button" class="btn btn-primary">Load from IcoMoon selection.json</button>
			</span>';


            $html = apply_filters('cs_icons_fields', $value, $id, $name, 'default');

            if ( isset($_POST['field']) && $_POST['field'] == 'icon' ) {
                echo json_encode(array( 'icon' => $html ));
                die;
            } else {
                return $html;
            }
        }

        public function add_category_to_list($wp_dp_atts = array()) {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;
            $wp_dp_defaults = array(
                'counter_category' => '',
                'category_id' => '',
                'wp_dp_category_name' => '',
                'wp_dp_category_parent' => '',
                'wp_dp_listing_taxonomy_icons' => '',
            );
            extract(shortcode_atts($wp_dp_defaults, $wp_dp_atts));

            foreach ( $_POST as $keys => $values ) {
                $$keys = $values;
            }

            if ( isset($_POST['wp_dp_category_name']) && $_POST['wp_dp_category_name'] <> '' ) {
                $wp_dp_featu_name = $_POST['wp_dp_category_name'];
            }

            if ( isset($_POST['wp_dp_category_parent']) && $_POST['wp_dp_category_parent'] <> '' ) {
                $wp_dp_category_parent = $_POST['wp_dp_category_parent'];
            }
            if ( isset($_POST['wp_dp_listing_taxonomy_icons']) && $_POST['wp_dp_listing_taxonomy_icons'] <> '' ) {
                $wp_dp_listing_taxonomy_icons = $_POST['wp_dp_listing_taxonomy_icons'];
            }


            if ( $category_id == '' && $counter_category == '' ) {
                $counter_category = $category_id = rand(1000000000, 9999999999);
            }

            $html = '
            <tr class="parentdelete" id="edit_track' . absint($counter_category) . '">
              <td id="subject-title' . absint($counter_category) . '" style="width:100%;">' . esc_attr($wp_dp_category_name) . '</td>
              <td id="subject-title' . absint($counter_category) . '" style="width:100%;"><i class="' . esc_attr($wp_dp_category_parent) . '"></i></td>

              <td class="centr" style="width:20%;"><a href="javascript:wp_dp_createpop(\'edit_track_form' . absint($counter_category) . '\',\'filter\')" class="actions edit">&nbsp;</a> <a  href="#"  data-catid=' . $counter_category . ' class="delete-it btndeleteit actions delete">&nbsp;</a></td>
              <td style="width:0"><div id="edit_track_form' . esc_attr($counter_category) . '" style="display: none;" class="table-form-elem">
                <input type="hidden" name="wp_dp_categorys_array[]" value="' . absint($category_id) . '" />
                  <div class="cs-heading-area">
                        <h5 style="text-align: left;">' . wp_dp_plugin_text_srt('wp_dp_listing_categorys') . '</h5>
                        <span onclick="javascript:wp_dp_removeoverlay(\'edit_track_form' . esc_js($counter_category) . '\',\'append\')" class="cs-btnclose"> <i class="icon-times"></i></span>
                        <div class="clear"></div>
                  </div>';

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_title'),
                'desc' => '',
                'hint_text' => '',
                'echo' => false,
                'field_params' => array(
                    'std' => $wp_dp_category_name,
                    'id' => 'category_name',
                    'return' => true,
                    'array' => true,
                    'extra_atr' => 'onchange="change_category_value(\'subject-title' . $counter_category . '\',this.value);"',
                    'force_std' => true,
                ),
            );

            $terms = get_terms(array(
                'taxonomy' => 'listing-category',
                'hide_empty' => false,
            ));
            $cats_parents = array();
            $cats_parents[''] = wp_dp_plugin_text_srt('wp_dp_listing_type_no_parent');

            foreach ( $terms as $term ) {

                $cats_parents[$term->term_id] = $term->name;
            }
            $html .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);




            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_parent'),
                'desc' => '',
                'hint_text' => '',
                'field_params' => array(
                    'std' => $wp_dp_category_parent,
                    'cust_name' => 'wp_dp_category_parent[]',
                    'classes' => 'dropdown chosen-select',
                    'options' => $cats_parents,
                    'return' => true,
                ),
            );



            $html .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
            $html .= '<div class="form-elements"><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label>' . wp_dp_plugin_text_srt('wp_dp_icon') . '</label></div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $html .= apply_filters('cs_icons_fields', $wp_dp_listing_taxonomy_icons, "listing_type_icon" . $counter_category, "wp_dp_listing_taxonomy_icon_array");

            $html .= '</div></div>';
            $wp_dp_opt_array = array(
                'name' => '',
                'desc' => '',
                'hint_text' => '',
                'field_params' => array(
                    'std' => '',
                    'return' => true,
                    'cust_name' => 'deleted_categories',
                    'array' => true,
                    'cust_type' => 'hidden',
                ),
            );
            $html .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
            $html .= '
                    <ul class="form-elements noborder">
                        <li class="to-label">
                          <label></label>
                        </li>
                        <li class="to-field">
                          <input type="button" value="' . wp_dp_plugin_text_srt('wp_dp_update_category') . '" onclick="wp_dp_removeoverlay(\'edit_track_form' . esc_js($counter_category) . '\',\'append\')" />
                        </li>
                    </ul>
                  </div>
                </td>
            </tr>';

            if ( isset($_POST['wp_dp_category_name']) ) {
                echo force_balance_tags($html);
            } else {
                return $html;
            }

            if ( isset($_POST['wp_dp_category_name']) ) {
                die();
            }
        }

        function wp_dp_post_page_elements_setting() {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_options;


            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_features_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_features_element_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'features_element',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_tags_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_tags_element_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'tags_element',
                    'return' => true,
                ),
            );
            
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_image_gallery_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_image_gallery_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'image_gallery_element',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_print_switch_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_print_switch_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'print_switch',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_claim_switch_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_claim_switch_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'claim_switch',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_flag_switch_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_flag_switch_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'flag_switch',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            /*$wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_location_map_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_location_map_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'location_element',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
             */

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_files_attchments_options_elements'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_files_attchments_options_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'attachments_options_element',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_allowed_extension'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_allowed_extension_desc'),
                'hint_text' => '',
                'echo' => true,
                'multi' => true,
                'field_params' => array(
                    'std' => 'txt,pdf,doc,docx',
                    'id' => 'listing_allowd_attachment_extensions',
                    'classes' => 'chosen-select-no-single',
                    'return' => true,
                    'options' => array(
                        'txt' => 'txt',
                        'rtf' => 'rtf',
                        'gif' => 'gif',
                        'jpg' => 'jpg',
                        'jpeg' => 'jpeg',
                        'png' => 'png',
                        'pdf' => 'pdf',
                        'doc' => 'doc',
                        'docx' => 'docx',
                        'xls' => 'xls',
                        'xlsx' => 'xlsx',
                        'ppt' => 'ppt',
                        'pptx' => 'pptx',
                        'bmp' => 'bmp',
                        'tif' => 'tif',
                        'csv' => 'csv',
                        'mp3' => 'mp3',
                        'ogg' => 'ogg',
                        'mp4' => 'mp4',
                        'webm' => 'webm',
                        'swf' => 'swf',
                        'rar' => 'rar',
                        'zip' => 'zip' ),
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_listing_video_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_meta_listing_video_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'video_element',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_add_listing_virtual_tour_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_add_listing_virtual_tour_meta_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'virtual_tour_element',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            do_action('wp_dp_listing_type_detail_options', $post->ID);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_near_by_options_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_near_by_options_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'near_by_options_element',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_yelp_places_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_yelp_places_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'yelp_places_element',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_walk_score_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_walk_score_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'walkscores_options_element',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_design_skeches_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_floor_design_skeches_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'floor_plans_options_element',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_similar_listings_switch'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_similar_listings_switch_hint'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'similar_listings_switch',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_html_fields->wp_dp_heading_render(array( 'name' =>  wp_dp_plugin_text_srt('wp_dp_detail_page_options_new'), ));
            $single_listing_options = array(
                'top_map' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_top_map'),
                'top_slider' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_top_slider'),
                'top_gallery_map' => wp_dp_plugin_text_srt('wp_dp_single_options_top_gallery_with_map'),
                'sticky_navigation' => wp_dp_plugin_text_srt('wp_dp_list_meta_sticky'),
                'content_gallery' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_content_gallery'),
                'social_networks' => wp_dp_plugin_text_srt('wp_dp_user_meta_social_networks'),
                'bottom_member_info' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_content_bottom_member_info'),
                'sidebar_map' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_map'),
                'sidebar_gallery' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_gallery'),
                'sidebar_member_info' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_member_info'),
                'sidebar_contact_info' => wp_dp_plugin_text_srt('wp_dp_single_options_sidebar_contact_info'),
                'sidebar_mortgage_calculator' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_mortgage_calculator'),
                 'sidebar_opening_hours' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_opening_hours'),
            );

            $wp_dp_listing_detail_page_view = isset($wp_dp_plugin_options['wp_dp_listing_detail_page_view']) ? $wp_dp_plugin_options['wp_dp_listing_detail_page_view'] : 'detail_view1';
            $wp_dp_listing_detail_page = get_post_meta($post->ID, 'wp_dp_listing_detail_page', true);
            $listing_detail_page_selected_view = 'detail_view5';

            if ( isset($single_listing_options) ) {
                $single_listing_views = array( 'detail_view5' );
                $exclude_options = array();
                $exclude_options['detail_view5'] = array( 'top_map', 'top_slider', 'social_networks' ); //, 'content_gallery'
                if ( isset($single_listing_views) ) {
                    foreach ( $single_listing_views as $single_listing_view ) {
                        if ( $single_listing_view == $listing_detail_page_selected_view ) {
                            $display_fields = 'block';
                        } else {
                            $display_fields = 'none';
                        }
                         $display_fields = 'block';
                        echo '<div id="detail_' . $single_listing_view . '_fields" style="display: ' . $display_fields . ';">';
                        foreach ( $single_listing_options as $key => $val ) {
                            if ( ! in_array($key, $exclude_options[$single_listing_view]) ) {
                                $std_val = isset($wp_dp_plugin_options['wp_dp_' . $single_listing_view . '_' . $key]) ? $wp_dp_plugin_options['wp_dp_' . $single_listing_view . '_' . $key] : 'on';
                                $wp_dp_opt_array = array(
                                    'name' => $val,
                                    'desc' => '',
                                    'label_desc' => sprintf(wp_dp_plugin_text_srt('wp_dp_list_listing_type_help_text'),$val),
                                    'hint_text' => '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => $std_val,
                                        'id' => $single_listing_view . '_' . $key,
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
                            }
                        }
                        echo '</div>';
                    }
                }
            }

            $wp_dp_html_fields->wp_dp_heading_render(array( 'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_heading') ));
            
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_key_details'),
                'label_desc' => '',
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_key_details_hint'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_key_details'),
                    'id' => 'listing_type_title_key_details',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
            
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_amenities'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_amenities_hint'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_amenities'),
                    'id' => 'listing_type_title_amenities',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_listing_desc'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_listing_desc_hint'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_listing_desc'),
                    'id' => 'listing_type_title_listing_desc',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_video'),
                'label_desc' =>  wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_video_hintt'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_video'),
                    'id' => 'listing_type_title_video',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            /////////

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_virtual_tour'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_virtual_tour_hint'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_virtual_tour'),
                    'id' => 'listing_type_title_virtual_tour',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_faq'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_faq_hint'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_faq'),
                    'id' => 'listing_type_title_faq',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_floor_plan'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_floor_plan_hint'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_floor_plan'),
                    'id' => 'listing_type_title_floor_plan',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_file_attachment'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_file_attachment_hint'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_file_attachment'),
                    'id' => 'listing_type_title_file_attachment',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_yelp_places'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_yelp_places_hint'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_yelp_places'),
                    'id' => 'listing_type_title_yelp_places',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_nearby_places'),
                'label_desc' =>  wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_nearby_places_hint'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_section_title_default_text_nearby_places'),
                    'id' => 'listing_type_title_nearby_places',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
        }

        public function wp_dp_save_post_categories($post_id) {
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return;
            }


            if ( get_post_type() == 'listing-type' ) {

                $listing_type_categories = array();
                $del_Cats = isset($_POST['deleted_categories']) ? $_POST['deleted_categories'] : '';
                $feature_label = isset($_POST['feature_label']) ? $_POST['feature_label'] : '';
                $enable_not_selected = isset($_POST['wp_dp_enable_not_selected']) ? $_POST['wp_dp_enable_not_selected'] : '';
                $wp_dp_feature_icon = isset($_POST['wp_dp_feature_icon']) ? $_POST['wp_dp_feature_icon'] : '';
                $wp_dp_feature_icon_group = isset($_POST['wp_dp_feature_icon_group']) ? $_POST['wp_dp_feature_icon_group'] : '';
                $feature_array = array();
                if ( ! empty($feature_label) ) {
                    foreach ( $feature_label as $key => $lablel ) {
                        if ( $lablel != '' ) {
                            $feature_array[] = $lablel;
                        }
                    }
                }
                $feature_icons = array();
                if ( ! empty($wp_dp_feature_icon) ) {
                    foreach ( $wp_dp_feature_icon as $icon ) {
                        $feature_icons[] = $icon;
                    }
                }
                $feature_icons_group = array();
                if ( ! empty($wp_dp_feature_icon_group) ) {
                    foreach ( $wp_dp_feature_icon_group as $icon_group ) {
                        $feature_icons_group[] = $icon_group;
                    }
                }
                update_post_meta($post_id, 'wp_dp_enable_not_selected', $enable_not_selected);
                update_post_meta($post_id, 'feature_lables', $feature_array);
                update_post_meta($post_id, 'wp_dp_feature_icon', $feature_icons);
                update_post_meta($post_id, 'wp_dp_feature_icon_group', $feature_icons_group);

                $wp_dp_categorys_array = isset($_POST['wp_dp_categorys_array']) ? $_POST['wp_dp_categorys_array'] : '';
                $wp_dp_listing_taxonomy_icon_array = isset($_POST['wp_dp_listing_taxonomy_icon_array']) ? $_POST['wp_dp_listing_taxonomy_icon_array'] : '';

                $delete_categories = explode(',', $del_Cats);
                if ( ! empty($delete_categories) ) {
                    foreach ( $delete_categories as $cat ) {
                        if ( $cat != '' ) {
                            wp_delete_term($cat, 'listing-category');
                        }
                    }
                }
                $wp_dp_category_parent = isset($_POST['wp_dp_category_parent']) ? $_POST['wp_dp_category_parent'] : '';
                $wp_dp_category_name_array = isset($_POST['wp_dp_category_name_array']) ? $_POST['wp_dp_category_name_array'] : '';
                $cats_array = array();
                if ( ! empty($wp_dp_category_name_array) ) {
                    foreach ( $wp_dp_category_name_array as $cat_key => $cat_val ) {

                        $cat_parent = isset($wp_dp_category_parent[$cat_key]) ? $wp_dp_category_parent[$cat_key] : '';
                        $cat_name = sanitize_title($cat_val, 'no-title');
                        $cat_display_name = $cat_val;

                        if ( term_exists(intval($wp_dp_categorys_array[$cat_key]), 'listing-category') ) {
                            $args = array(
                                'name' => $cat_display_name,
                                'parent' => $cat_parent
                            );
                            wp_update_term($wp_dp_categorys_array[$cat_key], 'listing-category', $args);
                            if ( isset($wp_dp_listing_taxonomy_icon_array[$cat_key]) ) {
                                update_term_meta($wp_dp_categorys_array[$cat_key], 'wp_dp_listing_taxonomy_icon', $wp_dp_listing_taxonomy_icon_array[$cat_key]);
                            }
                        } else {

                            if ( ! term_exists($cat_name, 'listing-category') ) {
                                $wp_dp_cat_args = array( 'cat_name' => $cat_display_name, 'category_description' => wp_dp_plugin_text_srt('wp_dp_category_description'), 'category_nicename' => $cat_display_name, 'category_parent' => $cat_parent, 'taxonomy' => 'listing-category' );

                                $inserted_post_id = wp_insert_category($wp_dp_cat_args);
                                $cats_array[] = $inserted_post_id;
                                if ( isset($wp_dp_listing_taxonomy_icon_array[$cat_key]) ) {
                                    update_term_meta($inserted_post_id, 'wp_dp_listing_taxonomy_icon', $wp_dp_listing_taxonomy_icon_array[$cat_key]);
                                }
                            }
                        }
                    }
                }
                if ( $cats_array != '' ) {
                    update_post_meta(get_the_id(), 'wp_dp_listing_type_categories', $cats_array);
                }
                wp_set_post_terms(get_the_ID(), $cats_array, 'listing-category', true);
            }
        }

        /**
         * Settins tab contents.
         */
        public function listing_type_settings_tab() {
            global $wp_dp_html_fields, $post;
            $listing_type_icon_image = get_post_meta($post->ID, 'wp_dp_listing_type_icon_image', true);

            $wp_dp_html_fields->wp_dp_heading_render(array( 'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_general_settings') ));
            $permalink = get_permalink();
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_title'),
                'hint_text' => '',
                'desc' => '<strong>'.__('Permalink:', 'wp-dp').' </strong><span id="sample-permalink"><a href="'.$permalink.'">'.$permalink.'</a></span>',
                'html_entity_decode' => true,
                'echo' => true,
                'field_params' => array(
                    'std' => get_the_title($post->ID),
                    'id' => 'listing_type_title',
                    'return' => true,
                ),
            );
            //$wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_type_icon_image'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_type_icon_image_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => $listing_type_icon_image,
                    'id' => 'listing_type_icon_image',
                    'classes' => 'small dropdown chosen-select',
                    'options' => array( 'icon' => wp_dp_plugin_text_srt('wp_dp_icon'), 'image' => wp_dp_plugin_text_srt('wp_dp_image') ),
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            $icon_display = $image_display = 'none';
            if ( $listing_type_icon_image == 'image' ) {
                $image_display = 'block';
            } else {
                $icon_display = 'block';
            }

            echo '<div id="listing-type-icon-holder" class="form-elements" style="display:' . $icon_display . '">';
            $type_icon = get_post_meta($post->ID, 'wp_dp_listing_type_icon', true);
            $type_icon_group = get_post_meta($post->ID, 'wp_dp_listing_type_icon_group', true);
            $type_icon = ( isset($type_icon[0]) ) ? $type_icon[0] : '';
            $type_icon_group = ( isset($type_icon_group[0]) ) ? $type_icon_group[0] : 'default';
            ?>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label><?php echo wp_dp_plugin_text_srt('wp_dp_listing_icon'); ?></label>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <?php
            echo apply_filters('cs_icons_fields', $type_icon, 'listing_type_icon', 'wp_dp_listing_type_icon', $type_icon_group);
            ?>
            </div>
                <?php
                echo '</div>';
                echo '<div id="listing-type-image-holder" style="display:' . $image_display . '">';
                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_small_image'),
                    'desc' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_small_image_desc'),
                    'echo' => true,
                    'id' => 'listing_type_image',
                    'field_params' => array(
                        'id' => 'listing_type_image',
                        'std' => ( isset($wp_dp_listing_type_image) ) ? $wp_dp_listing_type_image : '',
                        'return' => true,
                    ),
                );
                $wp_dp_html_fields->wp_dp_upload_file_field($wp_dp_opt_array);
                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_big_iamge'),
                    'desc' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_big_iamge_desc'),
                    'echo' => true,
                    'id' => 'listing_type_big_image',
                    'field_params' => array(
                        'id' => 'listing_type_big_image',
                        'std' => ( isset($wp_dp_listing_type_big_image) ) ? $wp_dp_listing_type_big_image : '',
                        'return' => true,
                    ),
                );
                $wp_dp_html_fields->wp_dp_upload_file_field($wp_dp_opt_array);
                echo '</div>';

                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_map_marker_image'),
                    'desc' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_marker_image_desc'),
                    'echo' => true,
                    'id' => 'listing_type_marker_image',
                    'field_params' => array(
                        'id' => 'listing_type_marker_image',
                        'std' => ( isset($wp_dp_listing_type_marker_image) ) ? $wp_dp_listing_type_marker_image : '',
                        'return' => true,
                    ),
                );
                $wp_dp_html_fields->wp_dp_upload_file_field($wp_dp_opt_array);

                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_map_marker_hover_image'),
                    'desc' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_marker_hover_image'),
                    'echo' => true,
                    'id' => 'listing_type_marker_hover_image',
                    'field_params' => array(
                        'id' => 'listing_type_marker_hover_image',
                        'std' => ( isset($wp_dp_listing_type_marker_hover_image) ) ? $wp_dp_listing_type_marker_hover_image : '',
                        'return' => true,
                    ),
                );
                $wp_dp_html_fields->wp_dp_upload_file_field($wp_dp_opt_array);

                $wp_dp_search_result_page = get_post_meta($post->ID, 'wp_dp_search_result_page', true);
                $field_args = array(
                    'depth' => 0,
                    'child_of' => 0,
                    'class' => 'chosen-select',
                    'sort_order' => 'ASC',
                    'sort_column' => 'post_title',
                    'show_option_none' => wp_dp_plugin_text_srt('wp_dp_select_a_page'),
                    'hierarchical' => '1',
                    'exclude' => '',
                    'include' => '',
                    'meta_key' => '',
                    'meta_value' => '',
                    'authors' => '',
                    'exclude_tree' => '',
                    'selected' => $wp_dp_search_result_page,
                    'echo' => 0,
                    'name' => 'wp_dp_search_result_page',
                    'post_type' => 'page'
                );
                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_search_result_page'),
                    'id' => 'wp_dp_search_result_page',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_search_result_page_desc'),
                    'echo' => true,
                    'hint_text' => '',
                    'std' => $wp_dp_search_result_page,
                    'args' => $field_args,
                    'return' => true,
                );
                $wp_dp_html_fields->wp_dp_custom_select_page_field($wp_dp_opt_array);

                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_opening_hour_time_lapse'),
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_opening_hour_time_lapse_desc'),
                    'hint_text' => '',
                    'echo' => true,
                    'field_params' => array(
                        'std' => '15',
                        'id' => 'opening_hours_time_gap',
                        'classes' => 'wp-dp-number-field',
                        'return' => true,
                    ),
                );

                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
            }

            public function listing_type_price_option() {
                global $wp_dp_html_fields, $post;

                /*
                 * Price Options
                 */
                $wp_dp_html_fields->wp_dp_heading_render(array( 'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_listing_price_options') ));
                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_listing_price'),
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_listing_price_desc'),
                    'desc' => '',
                    'hint_text' => '',
                    'echo' => true,
                    'field_params' => array(
                        'std' => '',
                        'id' => 'listing_type_price',
                        'extra_atr' => 'onclick="listing_type_price(\'wp_dp_listing_type_price\');"',
                        'return' => true,
                    ),
                );
                $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

                $listing_detail_page = get_post_meta($post->ID, 'wp_dp_listing_type_price', true);
                $display_style = ( $listing_detail_page == 'on' ) ? 'block' : 'none';
                echo '<div class="price-settings" style="display:' . $display_style . ';">';

                

                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_minimum_options_filter'),
                    'desc' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_minimum_options_filter_desc'),
                    'echo' => true,
                    'field_params' => array(
                        'std' => '1',
                        'classes' => 'wp-dp-number-field',
                        'id' => 'price_minimum_options',
                        'cust_type' => 'number',
                        'return' => true,
                    ),
                );
                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_maximum_options_filter'),
                    'desc' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_maximum_options_filter_desc'),
                    'echo' => true,
                    'field_params' => array(
                        'std' => '50',
                        'classes' => 'wp-dp-number-field wp-dp-range-field ',
                        'id' => 'price_max_options',
                        'cust_type' => 'number',
                        'extra_atr' => ' data-min="1" data-max="50" ',
                        'return' => true,
                    ),
                );
                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_man_max_interval'),
                    'desc' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_type_meta_man_max_interval_desc'),
                    'echo' => true,
                    'field_params' => array(
                        'std' => '50',
                        'classes' => 'wp-dp-number-field',
                        'id' => 'price_interval',
                        'cust_type' => 'number',
                        'return' => true,
                    ),
                );
                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_search_style'),
                    'desc' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_search_style_desc'),
                    'echo' => true,
                    'field_params' => array(
                        'std' => '',
                        'id' => 'listing_type_price_search_style',
                        'classes' => 'chosen-select-no-single',
                        'options' => array( 'slider' => wp_dp_plugin_text_srt('wp_dp_slider'), 'dropdown' => wp_dp_plugin_text_srt('wp_dp_dropdown_small') ),
                        'return' => true,
                    ),
                );
                $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);


                echo '</div>';
            }

            public function listing_type_by_category_slug($cate_slug) {
                $listing_types_cats = array();
                $args = array(
                    'posts_per_page' => "1",
                    'post_type' => 'listing-type',
                    'post_status' => 'publish',
                    'meta_query' => array(
                        array(
                            'key' => 'wp_dp_listing_type_cats',
                            'value' => serialize($cate_slug),
                            'compare' => 'Like',
                        ),
                    ),
                );

                $custom_query = new WP_Query($args);
                if ( $custom_query->have_posts() <> "" ) {
                    while ( $custom_query->have_posts() ): $custom_query->the_post();
                        global $post;
                        $listing_type_obj = $post;
                        return ($listing_type_obj->post_name);
                    endwhile;
                }
            }

        }


       //enqueue inline styles
        add_action( 'admin_enqueue_scripts', 'listing_type_title_inline_style' );
        function listing_type_title_inline_style() {
            global $post;
            if(isset($post) and $post->post_type === 'listing-type'){
                wp_add_inline_style( 'wp-admin', '#post-body-content { display: none !important; }' );
                wp_add_inline_style( 'wp-admin', '#edit-slug-box { padding: 0px !important; }' );
            }
        }

        //inline script to move title box inside metabox
        add_action( 'admin_enqueue_scripts', 'listing_type_title_inline_script' );
        function listing_type_title_inline_script() {
           global $post;
           if(isset($post) and $post->post_type === 'listing-type'){
                $html = "<div class='form-elements'>";
                $html .= "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>";
                $html .= "<label>".wp_dp_plugin_text_srt('wp_dp_listing_type_title')."</label>";
                $html .= "</div>";
                $html .= "<div id='title-box' class='col-lg-8 col-md-8 col-sm-12 col-xs-12' >";
                $html .= "</div>";
                $html .= "</div>";

                wp_add_inline_script( 'jquery-migrate', 'jQuery(window).load(function() {
                                                              var html = "'.$html.'";
                                                              jQuery(html).prependTo(".form-elements:eq(0)");
                                                              jQuery("#titlediv").css({"margin-bottom":"10px"}).detach().prependTo("#title-box");
                                                              jQuery("#edit-slug-box").remove();
                                                         });' );
           }
        }

        global $wp_dp_listing_type_meta;
        $wp_dp_listing_type_meta = new Wp_dp_Listing_Type_Meta();
        return $wp_dp_listing_type_meta;
    }