<?php
/**
 * File Type: Listings Shortcode Frontend
 */
if ( ! class_exists('Wp_dp_Shortcode_Listingsearch_Frontend') ) {

    class Wp_dp_Shortcode_Listingsearch_Frontend {

        /**
         * Constant variables
         */
        var $PREFIX = 'wp_dp_listingsearch';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_dp_listingsearch_shortcode_callback' ));
            add_action('wp_ajax_wp_dp_listingsearch_content', array( $this, 'wp_dp_listingsearch_content' ));
            add_action('wp_ajax_nopriv_wp_dp_listingsearch_content', array( $this, 'wp_dp_listingsearch_content' ));
        }

        /*
         * Shortcode View on Frontend
         */

        public function wp_dp_listingsearch_shortcode_callback($atts, $content = "") {
            $listing_short_counter = isset($atts['listing_counter']) && $atts['listing_counter'] != '' ? ( $atts['listing_counter'] ) : rand(123, 9999); // for shortcode counters
            $icons_groups = get_option('cs_icons_groups');
            if ( ! empty($icons_groups) ) {
                foreach ( $icons_groups as $icon_key => $icon_obj ) {
                    if ( isset($icon_obj['status']) && $icon_obj['status'] == 'on' ) {
                        wp_enqueue_style('cs_icons_data_css_' . $icon_key, $icon_obj['url'] . '/style.css');
                    }
                }
            }
            ob_start();
            $page_element_size = isset($atts['wp_dp_listingsearch_element_size']) ? $atts['wp_dp_listingsearch_element_size'] : 100;
            $advance_link = isset($atts['advance_link']) ? $atts['advance_link'] : '';
            $listingsearch_advance_filter_switch = isset($atts['listingsearch_advance_filter_switch']) ? $atts['listingsearch_advance_filter_switch'] : '';
            $listingsearch_title = isset($atts['listingsearch_title']) ? $atts['listingsearch_title'] : '';
            $listingsearch_subtitle = isset($atts['listingsearch_subtitle']) ? $atts['listingsearch_subtitle'] : '';
            $listingsearch_alignment = isset($atts['listingsearch_alignment']) ? $atts['listingsearch_alignment'] : '';
            $element_title_color = isset($atts['wp_dp_listing_search_element_title_color']) ? $atts['wp_dp_listing_search_element_title_color'] : '';
            $element_subtitle_color = isset($atts['wp_dp_listing_search_element_subtitle_color']) ? $atts['wp_dp_listing_search_element_subtitle_color'] : '';

            $wp_dp_listing_search_seperator_style = isset($atts['wp_dp_listing_search_seperator_style']) ? $atts['wp_dp_listing_search_seperator_style'] : '';



            $element_color_title = '';
            if ( isset($element_title_color) && $element_title_color != '' ) {
                $element_color_title = ' style="color:' . $element_title_color . ' ! important;"';
            }
            $listingsearch_view = isset($atts['listingsearch_view']) ? $atts['listingsearch_view'] : '';
            $search_background_color = isset($atts['search_background_color']) ? $atts['search_background_color'] : '';
            $search_backg_color_style = '';
            if ( isset($search_background_color) && ! empty($search_background_color) ) {
                $search_backg_color_style = ' style="background-color:' . $search_background_color . ' !important;"';
            }
            
            $modernv2_search_backg_color_style = '';
            if ( isset($search_background_color) && ! empty($search_background_color) ) {
                $modernv2_search_backg_color_style = ' style="background-color:' . wp_dp_hex2rgba($search_background_color, '0.8') . ' !important;"';
            }
            
            
            
            
            $medern_v2_search_backg_color_style = '';
            if ( isset($search_background_color) && ! empty($search_background_color) && ( $listingsearch_view == 'fancy_v3' || $listingsearch_view == 'default') ) {
                $medern_v2_search_backg_color_style = ' style="background-color:' . wp_dp_hex2rgba($search_background_color, '0.7') . ' !important;"';
            }
            
            
            $default_search_backg_color_style = '';
            if ( isset($search_background_color) && ! empty($search_background_color) && ($listingsearch_view == 'default') ) {
                $default_search_backg_color_style = ' style="background-color:' . wp_dp_hex2rgba($search_background_color, '0.3') . ' !important;"';
            }
            
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size, $atts) . ' ">';
            }
            wp_enqueue_script('wp-dp-listing-functions');
            wp_enqueue_script('wp_dp_location_autocomplete_js');
            $wp_dp_loc_strings = array(
                'plugin_url' => wp_dp::plugin_url(),
                'ajax_url' => admin_url('admin-ajax.php'),
            );
            if ( $listingsearch_view == 'list' ) {
                ?>
                <div class="main-search" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                    <?php
                    $wp_dp_element_structure = '';
                    $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                    echo force_balance_tags($wp_dp_element_structure);
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
                <?php
            } else if ( $listingsearch_view == 'classic' ) {
                $wp_dp_element_structure = '';
                $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                echo force_balance_tags($wp_dp_element_structure);
                ?>
                <div class="main-search classic" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                    <?php
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
                <?php
            } elseif ( $listingsearch_view == 'modern' ) {
                $wp_dp_element_structure = '';
                $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                echo force_balance_tags($wp_dp_element_structure);
                ?>
                <div class="main-search modern v2" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                    <?php
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
            <?php } elseif ( $listingsearch_view == 'modern_v2' ) {
                ?>
                <div class="main-search modern v3" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>" <?php echo wp_dp_allow_special_char($modernv2_search_backg_color_style); ?>>

                    <?php if ( ! empty($listingsearch_title) ) { ?>
                        <p class="search-heading" <?php echo wp_dp_allow_special_char($element_color_title); ?>>
                            <?php echo esc_html($listingsearch_title); ?>
                        </p>
                    <?php } ?>
                    <?php
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
            <?php } elseif ( $listingsearch_view == 'modern_v3' ) {
                ?>
                <div class="main-search modern v3 small-search" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>" <?php echo wp_dp_allow_special_char($search_backg_color_style); ?>>
                    <?php if ( ! empty($listingsearch_title) ) { ?>
                        <p class="search-heading" <?php echo wp_dp_allow_special_char($element_color_title); ?>>
                            <?php echo esc_html($listingsearch_title); ?>
                        </p>
                    <?php } ?>
                    <?php
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
            <?php } elseif ( $listingsearch_view == 'modern_v4' ) {
                ?>
                <div class="wp-dp-listing-content main-search dark-search" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>" <?php echo wp_dp_allow_special_char($search_backg_color_style); ?>>
                    <?php if ( ! empty($listingsearch_title) ) { ?>
                        <p class="search-heading" <?php echo wp_dp_allow_special_char($element_color_title); ?>>
                            <?php echo esc_html($listingsearch_title); ?>
                        </p>
                    <?php } ?>
                    <?php
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
                <?php
            } elseif ( $listingsearch_view == 'simple' ) {
                $wp_dp_element_structure = '';
                $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                echo force_balance_tags($wp_dp_element_structure);
                ?>
                <div class="main-search simple v2" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                    <?php
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
            <?php } elseif ( $listingsearch_view == 'advance' ) {
                ?>
                <div class="main-search advance" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                    <?php
                    $wp_dp_element_structure = '';
                    $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                    echo force_balance_tags($wp_dp_element_structure);
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
                <?php
            } elseif ( $listingsearch_view == 'fancy_v2' ) {
                $wp_dp_element_structure = '';
                $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                echo force_balance_tags($wp_dp_element_structure);
                ?>
                <div class="wp-dp-listing-content main-search fancy v2" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                    <ul id="nav-tabs-<?php echo esc_attr($listing_short_counter); ?>" class="nav nav-tabs" role="tablist">
                        <li <?php echo wp_dp_allow_special_char($search_backg_color_style); ?> class="active"><a href="javascript:void(0);" onclick="wp_dp_advanced_search_field('<?php echo esc_attr($listing_short_counter); ?>', 'simple', this);"><?php echo wp_dp_plugin_text_srt('wp_dp_listsearch_best_home'); ?></a></li>
                        <?php if ( $listingsearch_advance_filter_switch == 'yes' ) { ?>
                            <li <?php echo wp_dp_allow_special_char($search_backg_color_style); ?> class=""><a href="javascript:void(0);" onclick="wp_dp_advanced_search_field('<?php echo esc_attr($listing_short_counter); ?>', 'advance', this);"><?php echo wp_dp_plugin_text_srt('wp_dp_listsearch_advanced'); ?></a></li>
                        <?php } ?>
                    </ul> 
                    <div <?php echo wp_dp_allow_special_char($search_backg_color_style); ?> id="Listing-content-<?php echo esc_html($listing_short_counter); ?>" class="tab-content">
                        <?php
                        $listing_arg = array(
                            'listing_short_counter' => $listing_short_counter,
                            'atts' => $atts,
                            'content' => $content,
                        );
                        $this->wp_dp_listingsearch_content($listing_arg);
                        ?>
                    </div>
                </div>
            <?php } elseif ( $listingsearch_view == 'fancy_v3' ) { ?>
                <div class="wp-dp-listing-content main-search fancy v3" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>" <?php echo wp_dp_allow_special_char($medern_v2_search_backg_color_style); ?>>
                    <?php
                    $wp_dp_element_structure = '';
                    $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                    echo force_balance_tags($wp_dp_element_structure);
                    ?>
                    <?php
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
            <?php } elseif ( $listingsearch_view == 'fancy_v4' ) { ?>
                <div class="wp-dp-listing-content main-search fancy v3 plain" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>" <?php echo wp_dp_allow_special_char($search_backg_color_style); ?>>
                    <?php
                    $wp_dp_element_structure = '';
                    $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                    echo force_balance_tags($wp_dp_element_structure);
                    ?>
                    <?php
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
            <?php } elseif ( $listingsearch_view == 'default' ) { ?>
                <?php
                $wp_dp_element_structure = '';
                $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                echo force_balance_tags($wp_dp_element_structure);
                ?>
                <div class="wp-dp-listing-content fancy-search " id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>" <?php echo wp_dp_allow_special_char($default_search_backg_color_style); ?>>
                    <?php
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                    );
                    $this->wp_dp_listingsearch_content($listing_arg);
                    ?>
                </div>
            <?php } else { ?>
                <div class="wp-dp-listing-content main-search fancy" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                    <?php
                    $wp_dp_element_structure = '';
                    $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listingsearch_title, $listingsearch_subtitle, $listingsearch_alignment, $element_title_color, $wp_dp_listing_search_seperator_style, $element_subtitle_color);
                    echo force_balance_tags($wp_dp_element_structure);
                    ?>
                    <ul id="nav-tabs-<?php echo esc_attr($listing_short_counter); ?>" class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="javascript:void(0);" onclick="wp_dp_advanced_search_field('<?php echo esc_attr($listing_short_counter); ?>', 'simple', this);"><?php echo wp_dp_plugin_text_srt('wp_dp_listsearch_best_home'); ?></a></li>
                        <?php if ( $listingsearch_advance_filter_switch == 'yes' ) { ?>
                            <li class=""><a href="javascript:void(0);" onclick="wp_dp_advanced_search_field('<?php echo esc_attr($listing_short_counter); ?>', 'advance', this);"><?php echo wp_dp_plugin_text_srt('wp_dp_listsearch_advanced'); ?></a></li>
                        <?php } ?>
                    </ul> 
                    <div id="Listing-content-<?php echo esc_html($listing_short_counter); ?>" class="tab-content">
                        <?php
                        $listing_arg = array(
                            'listing_short_counter' => $listing_short_counter,
                            'atts' => $atts,
                            'content' => $content,
                        );
                        $this->wp_dp_listingsearch_content($listing_arg);
                        ?>
                    </div>
                </div>
                <?php
            }
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }
            $html = ob_get_clean();
            return $html;
        }

        public function wp_dp_listingsearch_content($listing_arg = '') {
            global $wpdb, $wp_dp_form_fields_frontend, $wp_dp_plugin_options;
            // getting arg array from ajax

            if ( isset($listing_arg) && $listing_arg != '' && ! empty($listing_arg) ) {
                extract($listing_arg);
            }

            $default_date_time_formate = 'd-m-Y H:i:s';

            $element_listing_sort_by = isset($atts['listing_sort_by']) ? $atts['listing_sort_by'] : 'no';
            $element_listing_topmap = isset($atts['listing_topmap']) ? $atts['listing_topmap'] : 'no';
            $element_listing_topmap_position = isset($atts['listing_topmap_position']) ? $atts['listing_topmap_position'] : 'full';
            $element_listing_layout_switcher = isset($atts['listing_layout_switcher']) ? $atts['listing_layout_switcher'] : 'no';
            $element_listing_layout_switcher_view = isset($atts['listing_layout_switcher_view']) ? $atts['listing_layout_switcher_view'] : 'grid';

            $element_listing_search_keyword = isset($atts['listing_search_keyword']) ? $atts['listing_search_keyword'] : 'no';
            $listing_listing_featured = isset($atts['listing_featured']) ? $atts['listing_featured'] : 'all';

            $listing_type = isset($atts['listing_type']) ? $atts['listing_type'] : '';
            $search_box = isset($atts['search_box']) ? $atts['search_box'] : 'no';
            $popup_link_text = isset($atts['popup_link_text']) ? $atts['popup_link_text'] : '';
            $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
            $wp_dp_search_result_page = ( $wp_dp_search_result_page != '' ) ? wp_dp_wpml_lang_page_permalink($wp_dp_search_result_page, 'page') : '';
            // Listing Search View
            $listingsearch_view = isset($atts['listingsearch_view']) ? $atts['listingsearch_view'] : 'fancy';

            set_query_var('listing_arg', $listing_arg);
            set_query_var('popup_link_text', $popup_link_text);
            set_query_var('content', $content);
            set_query_var('atts', $atts);
            set_query_var('wp_dp_search_result_page', $wp_dp_search_result_page);
            set_query_var('listing_short_counter', $listing_short_counter);

            if ( $listingsearch_view == 'list' ) {
                wp_dp_get_template_part('listingsearch', 'list-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'classic' ) {
                wp_dp_get_template_part('listingsearch', 'classic-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'modern' ) {
                wp_dp_get_template_part('listingsearch', 'modern-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'modern_v2' ) {
                wp_dp_get_template_part('listingsearch', 'modern-v2-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'modern_v3' ) {
                wp_dp_get_template_part('listingsearch', 'modern-v3-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'modern_v4' ) {
                wp_dp_get_template_part('listingsearch', 'modern-v4-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'simple' ) {
                wp_dp_get_template_part('listingsearch', 'simple-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'advance' ) {
                wp_dp_get_template_part('listingsearch', 'advance-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'fancy_v2' ) {
                wp_dp_get_template_part('listingsearch', 'fance-v2-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'fancy_v3' ) {
                wp_dp_get_template_part('listingsearch', 'fance-v3-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'fancy_v4' ) {
                wp_dp_get_template_part('listingsearch', 'fance-v4-filters', 'listingsearch');
            } else if ( $listingsearch_view == 'default' ) {
                wp_dp_get_template_part('listingsearch', 'default-filters', 'listingsearch');
            } else {
                wp_dp_get_template_part('listingsearch', 'filters', 'listingsearch');
            }
            ?>
            <script>
                if (jQuery('.chosen-select, .chosen-select-deselect, .chosen-select-no-single, .chosen-select-no-results, .chosen-select-width').length != '') {
                    var config = {
                        '.chosen-select': {width: "100%"},
                        '.chosen-select-deselect': {allow_single_deselect: true},
                        '.chosen-select-no-single': {disable_search_threshold: 10, width: "100%"},
                        '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
                        '.chosen-select-width': {width: "95%"}
                    };
                    for (var selector in config) {
                        jQuery(selector).chosen(config[selector]);
                    }
                }
            </script>
            <?php
            // only for ajax request
            if ( isset($_REQUEST['action']) && $_REQUEST['action'] != 'editpost' ) {
				//die();
            }
        }

    }

    global $wp_dp_shortcode_listingsearch_frontend;
    $wp_dp_shortcode_listingsearch_frontend = new Wp_dp_Shortcode_Listingsearch_Frontend();
}

