<?php
/*
 * listing taxonomy mata
 */
if ( ! class_exists('Listing_taxonomy_Meta') ) {

    Class Listing_taxonomy_Meta {

        public function __construct() {
            add_action('listing-category_add_form_fields', array( $this, 'icon_taxonomy_add_new_meta_field' ), 10, 2);
            add_action('listing-category_edit_form_fields', array( $this, 'icon_taxonomy_edit_meta_field' ), 10, 2);
            add_action('edited_listing-category', array( $this, 'save_taxonomy_custom_meta' ), 10, 2);
            add_action('create_listing-category', array( $this, 'save_taxonomy_custom_meta' ), 10, 2);

            //manage extra columns 
            add_filter('manage_edit-listing-category_columns', array( $this, 'listing_category_columns' ));
            add_filter('manage_listing-category_custom_column', array( $this, 'listing_category_columns_content' ), 10, 3);
            add_action('admin_head', array( $this, 'check_post_type_and_remove_media_buttons' ));
        }
        
        function check_post_type_and_remove_media_buttons() {
            $screen = get_current_screen();
            if ( isset($screen->id) && $screen->id == 'edit-listing-category' ) {
               echo '<style type="text/css">';
               echo '.post-type-listings .column-icon { width:30px !important; overflow:hidden }';
               echo '.post-type-listings .column-posts { width:150px !important; overflow:hidden }';
               echo '.term-description-wrap { display:none; }';
               echo '</style>';
            }
        }
        
        public function listing_category_columns($columns) {

            unset($columns['description']);

            foreach ( $columns as $key => $value ) {
                $new_columns[$key] = $value;
                if ( $key == 'cb' ) {
                    $new_columns['icon'] = '<i class="dashicons dashicons-format-image"></i>';
                }
            }
          $new_columns['posts'] = wp_dp_plugin_text_srt('wp_dp_num_of_listing');
            return $new_columns;
        }

        public function listing_category_columns_content($content, $column_name, $term_id) {

            if ( 'name' == $column_name ) {
                $term_meta = get_term_meta($term_id, 'wp_dp_listing_taxonomy_icon', true);
                $content = '<i data-fip-value="' . $term_meta . '" class="' . $term_meta . '"></i>';
            }

            if ( 'icon' == $column_name ) {
                $term_meta = get_term_meta($term_id, 'wp_dp_listing_taxonomy_icon', true);
                $content = '<i data-fip-value="' . $term_meta . '" class="' . $term_meta . '"></i>';
            }
            
            return $content;
        }

        function icon_taxonomy_add_new_meta_field($term) {
            // this will add the custom meta field to the add new term page
            $type_icon = ( isset($type_icon[0]) ) ? $type_icon[0] : '';
            ?>
            <div class="form-field term-slug-wrap">
                <div class="form-elements">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label><?php echo wp_dp_plugin_text_srt('wp_dp_icon'); ?></label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?php echo apply_filters('cs_icons_fields', '', 'listing_type_icon', 'wp_dp_listing_taxonomy_icon'); ?>
                    </div>
                </div>
            </div>
            <?php
        }

        public function icon_taxonomy_edit_meta_field($term) {
            $t_id = $term->term_id;
            // retrieve the existing value(s) for this meta field. This returns an array
            $term_meta = get_term_meta($t_id, 'wp_dp_listing_taxonomy_icon', true);
            $icon_group = get_term_meta($t_id, 'wp_dp_listing_taxonomy_icon_group', true);
            $icon_group = ( isset($icon_group) && $icon_group != '' ) ? $icon_group : 'default';
            ?>


            <div class="form-field term-slug-wrap">
                <div class="form-elements">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label><?php echo wp_dp_plugin_text_srt('wp_dp_icon'); ?></label>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <?php echo apply_filters('cs_icons_fields', $term_meta, 'listing_type_icon', 'wp_dp_listing_taxonomy_icon', $icon_group); ?>
                    </div>
                </div>
            </div>
            <?php
        }

        public function save_taxonomy_custom_meta($term_id) {
            if ( isset($_POST['wp_dp_listing_taxonomy_icon']) ) {

                $icon = $_POST['wp_dp_listing_taxonomy_icon'][0];
                $icon_group = $_POST['wp_dp_listing_taxonomy_icon_group'][0];

                $t_id = $term_id;

                // Save the option array.
                update_term_meta($t_id, 'wp_dp_listing_taxonomy_icon', $icon);
                update_term_meta($t_id, 'wp_dp_listing_taxonomy_icon_group', $icon_group);
            }
        }

    }

    $Listing_taxonomy_Meta = new Listing_taxonomy_Meta();
}