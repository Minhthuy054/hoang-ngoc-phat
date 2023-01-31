<?php
/**
 * File Type: Promotions Settings
 */
if ( ! class_exists('Wp_dp_Promotions') ) {

    class Wp_dp_Promotions {

        var $fixed_promotions = array();

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_filter('wp_dp_plugin_option_addon_tab', array( $this, 'wp_dp_plugin_option_addon_tab_callback' ), 10, 1);
            add_filter('wp_dp_plugin_option_addon_content', array( $this, 'wp_dp_plugin_option_addon_content_callback' ), 10, 1);
            add_filter('wp_dp_plugin_options_fields_promotions', array( $this, 'wp_dp_plugin_options_fields_callback' ), 10, 1);
            add_action('wp_ajax_add_promotions_opt', array( $this, 'add_promotions_opt_callback' ), 10);
            add_filter('wp_dp_plugin_options_save', array( $this, 'wp_dp_plugin_options_save_callback' ), 10, 1);
        }

        /*
         * Add Menu in plugin settings tab
         */

        public function wp_dp_plugin_option_addon_tab_callback($wp_dp_setting_options) {

            $wp_dp_setting_options[] = array(
                "name" => wp_dp_plugin_text_srt('wp_dp_promotions_settings'),
                "fontawesome" => 'icon-build',
                "id" => "tab-promotions-page-settings",
                "std" => "",
                "type" => "main-heading",
                "options" => ''
            );
            return $wp_dp_setting_options;
        }

        /*
         * Add Menu in plugin settings tab content
         */

        public function wp_dp_plugin_option_addon_content_callback($wp_dp_setting_options) {

            $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_promotions_settings'),
                "id" => "tab-promotions-page-settings",
                "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_promotions_settings') . '"',
                "type" => "sub-heading",
                "help_text" => "",
            );

            $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_promotions_settings'),
                "id" => "tab-promotions-settings",
                "std" => wp_dp_plugin_text_srt('wp_dp_promotions_settings'),
                "type" => "section",
                "options" => ""
            );

            $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_promotions_switch'),
                "desc" => "",
                "hint_text" => "",
                "id" => "promotions_switch",
                "std" => "on",
                "type" => "checkbox"
            );

            $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_promotions_popup_footer'),
                "desc" => "",
                "hint_text" => '',
                "label_desc" => '',
                "id" => "promotions_popup_footer_text",
                "std" => "",
                "type" => "textarea",
                'wp_dp_editor' => true,
            );

            $wp_dp_setting_options[] = array( "name" => "",
                "desc" => "",
                "id" => "promotions-settings",
                "type" => "promotions_settings",
            );

            $wp_dp_setting_options[] = array( "col_heading" => "",
                "type" => "col-right-text",
                "help_text" => ""
            );

            return $wp_dp_setting_options;
        }

        /*
         * Plugin Options Field 
         */

        public function wp_dp_plugin_options_fields_callback($field_obj = array()) {
            global $wp_dp_plugin_options, $wp_dp_form_fields;
            $this->fixed_promotions['top-categories'] = array(
                'title' => wp_dp_plugin_text_srt('wp_dp_promotion_top_categories'),
                'background' => '#1e73be',
                'description' => wp_dp_plugin_text_srt('wp_dp_promotion_top_categories_desc'),
                'duration' => 7,
                'price' => 50,
                'slug' => 'top-categories',
            );

            $this->fixed_promotions['home-featured'] = array(
                'title' => wp_dp_plugin_text_srt('wp_dp_promotion_home_featured'),
                'background' => '#1e73be',
                'description' => wp_dp_plugin_text_srt('wp_dp_promotion_home_featured_desc'),
                'duration' => 10,
                'price' => 20,
                'slug' => 'home-featured',
            );

            $this->fixed_promotions['urgent'] = array(
                'title' => wp_dp_plugin_text_srt('wp_dp_promotion_urgent'),
                'background' => '#dd3333',
                'description' => wp_dp_plugin_text_srt('wp_dp_promotion_urgent_desc'),
                'duration' => 15,
                'price' => 30,
                'slug' => 'urgent',
            );
            $output = '';
            if ( isset($field_obj['type']) && $field_obj['type'] == 'promotions_settings' ) {
                $wp_dp_promotions = isset($wp_dp_plugin_options['wp_dp_promotions']) ? $wp_dp_plugin_options['wp_dp_promotions'] : '';
                if(!is_array($wp_dp_promotions)){
                    $wp_dp_promotions   =   array();
                }
                $wp_dp_promotions = array_merge($this->fixed_promotions, $wp_dp_promotions);
                ob_start();
                ?>
                <div class="wp-dp-list-wrap">
                    <ul class="wp-dp-list-layout">
                        <li class="wp-dp-list-label">
                            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_options_promotion_title'); ?></label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_options_promotion_background_color'); ?></label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_options_description'); ?> </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_options_promotion_no_of_days'); ?> </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_options_price'); ?> </label>
                                </div>
                            </div>
                        </li>
                        <?php
                        $counter = 0;
                        if ( is_array($wp_dp_promotions) && sizeof($wp_dp_promotions) > 0 ) {
                            foreach ( $wp_dp_promotions as $key => $promotion_obj ) {
                                ?>
                                <li class="wp-dp-list-item">
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => isset($promotion_obj['title']) ? esc_html($promotion_obj['title']) : '',
                                                    'cust_name' => 'wp_dp_promotions[' . $counter . '][title]',
                                                    'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_promotion_title') . '"',
                                                    'classes' => 'input-field',
                                                );
                                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => isset($promotion_obj['background']) ? esc_html($promotion_obj['background']) : '',
                                                    'cust_name' => 'wp_dp_promotions[' . $counter . '][background]',
                                                    'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_promotion_background') . '"',
                                                    'classes' => 'input-field bg_color',
                                                );
                                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        jQuery('.bg_color').wpColorPicker();
                                    </script>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => isset($promotion_obj['description']) ? esc_html($promotion_obj['description']) : '',
                                                    'cust_name' => 'wp_dp_promotions[' . $counter . '][description]',
                                                    'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_description') . '"',
                                                    'classes' => 'input-field',
                                                );
                                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => isset($promotion_obj['duration']) ? esc_html($promotion_obj['duration']) : '',
                                                    'cust_name' => 'wp_dp_promotions[' . $counter . '][duration]',
                                                    'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_duration') . '"',
                                                    'classes' => 'input-field',
                                                    'cust_type' => 'number'
                                                );
                                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => isset($promotion_obj['price']) ? esc_html($promotion_obj['price']) : '',
                                                    'cust_name' => 'wp_dp_promotions[' . $counter . '][price]',
                                                    'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_price') . '"',
                                                    'classes' => 'input-field',
                                                    'cust_type' => 'number'
                                                );
                                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ( ! isset($this->fixed_promotions[$key]) ) { ?>
                                        <a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a>
                                    <?php
                                    }

                                    $wp_dp_opt_array = array(
                                        'std' => isset($promotion_obj['slug']) ? esc_html($promotion_obj['slug']) : '',
                                        'cust_name' => 'wp_dp_promotions[' . $counter . '][slug]',
                                        'extra_atr' => '',
                                        'classes' => 'input-field',
                                    );
                                    $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_array);
                                    ?>
                                </li>
                                <?php
                                $counter ++;
                            }
                        }
                        ?>
                    </ul>
                    <ul class="wp-dp-list-button-ul">
                        <li class="wp-dp-list-button">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <!--For Simple Input Element-->
                                <div class="input-element">
                                    <a href="javascript:void(0);" id="click-more" class="wp-dp-add-more cntrl-add-new-row add-promotions-opt"><?php echo wp_dp_plugin_text_srt('wp_dp_options_add_more'); ?></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <?php
                $output = ob_get_clean();
            }
            return $output;
        }

        /*
         * Add More Fields for Currencies
         */

        public function add_promotions_opt_callback() {
            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text, $wp_dp_plugin_options;
            $counter = isset($_REQUEST['currency_counter']) ? $_REQUEST['currency_counter'] + 1 : 0;
            ?>
            <li class="wp-dp-list-item">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <!--For Simple Input Element-->
                    <div class="input-element">
                        <div class="input-holder">
                            <?php
                            $wp_dp_opt_array = array(
                                'std' => '',
                                'cust_name' => 'wp_dp_promotions[' . $counter . '][title]',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_promotion_title') . '"',
                                'classes' => 'input-field',
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <!--For Simple Input Element-->
                    <div class="input-element">
                        <div class="input-holder">
                            <?php
                            $wp_dp_opt_array = array(
                                'std' => '',
                                'cust_name' => 'wp_dp_promotions[' . $counter . '][background]',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_promotion_background') . '"',
                                'classes' => 'input-field bg_color',
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    jQuery('.bg_color').wpColorPicker();
                </script>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <!--For Simple Input Element-->
                    <div class="input-element">
                        <div class="input-holder">
                            <?php
                            $wp_dp_opt_array = array(
                                'std' => '',
                                'cust_name' => 'wp_dp_promotions[' . $counter . '][description]',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_description') . '"',
                                'classes' => 'input-field',
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <!--For Simple Input Element-->
                    <div class="input-element">
                        <div class="input-holder">
                            <?php
                            $wp_dp_opt_array = array(
                                'std' => '',
                                'cust_name' => 'wp_dp_promotions[' . $counter . '][duration]',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_duration') . '"',
                                'classes' => 'input-field',
                                'cust_type' => 'number'
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <!--For Simple Input Element-->
                    <div class="input-element">
                        <div class="input-holder">
                            <?php
                            $wp_dp_opt_array = array(
                                'std' => '',
                                'cust_name' => 'wp_dp_promotions[' . $counter . '][price]',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_price') . '"',
                                'classes' => 'input-field',
                                'cust_type' => 'number'
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a>
            </li>
            <?php
            wp_die();
        }

        /*
         * Saving Promotions in plugin options
         */

        public function wp_dp_plugin_options_save_callback($postObj) {
            global $wp_dp_plugin_options, $wp_dp_form_fields;
            $promotions_array = array();
            $promotions = isset($postObj['wp_dp_promotions']) ? $postObj['wp_dp_promotions'] : array();
            if ( ! empty($promotions) ) {

                foreach ( $promotions as $key => $promotionArray ) {
                    $slug = isset($promotionArray['slug']) ? $promotionArray['slug'] : '';
                    if ( $slug == '' ) {
                        $slug = isset($promotionArray['title']) ? sanitize_title($promotionArray['title']) : $key;
                        $promotionArray['slug'] = $slug;
                    }
                    $promotions_array[$slug] = $promotionArray;
                }
                $postObj['wp_dp_promotions'] = $promotions_array;
            }
            return $postObj;
        }

    }

    global $wp_dp_promotions;
    $wp_dp_promotions = new Wp_dp_Promotions();
}