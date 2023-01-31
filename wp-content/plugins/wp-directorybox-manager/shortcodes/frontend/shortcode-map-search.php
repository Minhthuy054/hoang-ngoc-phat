<?php
/**
 * File Type: Searchs Shortcode Frontend
 */
if ( ! class_exists('Wp_dp_Shortcode_Map_Search_front') ) {

    class Wp_dp_Shortcode_Map_Search_front {

        /**
         * Constant variables
         */
        var $PREFIX = 'map_search';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_dp_map_search_shortcode_callback' ));
            add_action('wp_ajax_wp_dp_top_map_search', array( $this, 'map_search_query' ));
            add_action('wp_ajax_nopriv_wp_dp_top_map_search', array( $this, 'map_search_query' ));
            add_action('wp_ajax_quick_view_content', array( $this, 'quick_view_content_callback' ));
            add_action('wp_ajax_nopriv_quick_view_content', array( $this, 'quick_view_content_callback' ));
        }

        /*
         * Shortcode View on Frontend
         */

        public function wp_dp_map_search_shortcode_callback($atts, $content = "") {
            global $column_container, $wp_dp_form_fields_frontend, $wp_dp_plugin_options, $wp_dp_search_fields;
            wp_enqueue_script('wp_dp_location_autocomplete_js');
            if (!wp_script_is( 'wp-dp-google-map-api', 'enqueued' )) {
                wp_enqueue_script('wp-dp-google-map-api');
            }
            $html = '';
            $main_sections_columns = json_encode($column_container);
            $main_sections_columns = json_decode($main_sections_columns, true);
            $main_sections_column = isset($main_sections_columns['@attributes']['wp_dp_cs_section_view']) ? $main_sections_columns['@attributes']['wp_dp_cs_section_view'] : 'wide';
            $wp_dp_drawing_tools = isset($wp_dp_plugin_options['wp_dp_drawing_tools']) ? $wp_dp_plugin_options['wp_dp_drawing_tools'] : '';

            $map_search_title = isset($atts['map_search_title']) ? $atts['map_search_title'] : '';
            $map_search_subtitle = isset($atts['map_search_subtitle']) ? $atts['map_search_subtitle'] : '';
            $map_map_search_switch = isset($atts['map_map_search_switch']) ? $atts['map_map_search_switch'] : '';
            $map_map_search_height = isset($atts['map_map_search_height']) ? $atts['map_map_search_height'] : '400';
            $map_search_box_switch = isset($atts['map_search_box_switch']) ? $atts['map_search_box_switch'] : '';
            $map_search_result_page = isset($atts['map_search_result_page']) ? $atts['map_search_result_page'] : '';

            $listingsearch_title_switch = isset($atts['map_search_title_field_switch']) ? $atts['map_search_title_field_switch'] : '';
            $listingsearch_listing_type_switch = isset($atts['map_search_listing_type_field_switch']) ? $atts['map_search_listing_type_field_switch'] : '';
            $listingsearch_location_switch = isset($atts['map_search_location_field_switch']) ? $atts['map_search_location_field_switch'] : '';
            $listingsearch_categories_switch = isset($atts['map_search_categories_field_switch']) ? $atts['map_search_categories_field_switch'] : '';
            $listingsearch_price_switch = isset($atts['map_search_price_field_switch']) ? $atts['map_search_price_field_switch'] : '';
            $split_map = isset($atts['split_map']) ? $atts['split_map'] : false;
            $listingsearch_advance_filter_switch = isset($atts['map_search_advance_filter_switch']) ? $atts['map_search_advance_filter_switch'] : '';
            $listing_types_array = array();
            $listing_type_slug = '';

            $to_result_page = $map_search_result_page != '' ? get_permalink($map_search_result_page) : '';

            wp_enqueue_script('map-infobox');
            wp_enqueue_script('map-clusterer');

            $wp_dp_listing_strings = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'plugin_url' => wp_dp::plugin_url(),
                'alert_name' => wp_dp_plugin_text_srt('wp_dp_alerts_alert_name_title'),
                'alert_email' => wp_dp_plugin_text_srt('wp_dp_alerts_email_address'),
            );
            wp_localize_script('wp-dp-listing-top-map', 'wp_dp_top_gmap_strings', $wp_dp_listing_strings);
            wp_enqueue_script('wp-dp-listing-top-map');
            wp_enqueue_script('wp-dp-validation-script');
            ob_start();
            $rand_numb = rand(999, 999999);
            $atts['rand_numb'] = $rand_numb;
            $loc_polygon = '';
            if ( isset($_REQUEST['loc_polygon']) && $_REQUEST['loc_polygon'] != '' ) {
                $loc_polygon = $_REQUEST['loc_polygon'];
            }
            $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
            $wp_dp_search_result_page = ( $wp_dp_search_result_page != '' ) ? wp_dp_wpml_lang_page_permalink($wp_dp_search_result_page, 'page') : '';
            $map_div_classes = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 wp-dp-map-holder';
            if ( $split_map == true ) {
                $map_div_classes = 'col-lg-7 col-md-7 col-sm-12 col-xs-12 split-map-holder';
            }
            ?>
            <div class="<?php echo esc_attr($map_div_classes); ?>">
                <div class="map-loader-holder"><i class="fancy-spinner"></i></div>
                <script>
                    var map = '';
                    var infoMarker = '';
                    var info_open_info_window = '';
                    var reset_top_map_marker = [];
                    var markerClusterers;
                </script>
                <form name="wp-dp-top-map-form" id="frm_listing_arg<?php echo absint($rand_numb); ?>" action="<?php echo wp_dp_cs_allow_special_char($wp_dp_search_result_page); ?>" data-id="<?php echo absint($rand_numb); ?>" >
                    <div style="display:none" id='atts'><?php
                        echo json_encode($atts);
                        ?>
                    </div>
                    <div id="wp-dp-top-map-holder" class="wp-dp-top-map-holder">
                        <?php
                        $search_box_class = '';
                        $has_map_box_class = '';
                        if ( $map_map_search_switch == 'yes' ) {
                            $search_box_class = ' map-margin-top has-bg-color';
                            $has_map_box_class = ' has-map-search';

                            $draw_on_map_disable = '';
                            $draw_on_map_display = '';
                            $delete_on_map_display = 'display:none;';
                            if ( ( isset($_REQUEST['loc_polygon']) && $_REQUEST['loc_polygon'] != '' ) || ( isset($_REQUEST['location']) && $_REQUEST['location'] != '' ) ) {
                                $draw_on_map_disable = ' is-disabled';
                                $draw_on_map_display = 'display:none;';
                                $delete_on_map_display = '';
                            }
                            ?> 
                            <!-- start draw on map -->
                            <ul class="map-actions">

                                                                                                                                                                                                                                                                    <li><a data-placement="bottom" data-toggle="tooltip" onclick="wp_dp_getLocation('<?php echo absint($rand_numb) ?>');" title="<?php echo wp_dp_plugin_text_srt('wp_dp_map_search_geo_location'); ?>" id="geo-location-button-<?php echo absint($rand_numb) ?>" ><i class="icon-navigation"></i><!--<img src="<?php echo wp_dp::plugin_url() ?>assets/frontend/images/geo.svg" alt="">--></a></li>
                                <?php
                                if ( wp_is_mobile() ) {
                                    ?>
                                                                                                                                                                                                                                                                                                                            <li data-placement="bottom" data-toggle="tooltip" title="<?php echo wp_dp_plugin_text_srt('wp_dp_map_search_map_unlock'); ?>" id="top-gmap-lock-btn" class="top-gmap-lock-btn map-loked"><i class="icon-lock_outline"></i><!--<img src="<?php echo wp_dp::plugin_url() ?>assets/frontend/images/lock_on.svg" alt="">--></li>                                                        
                                    <?php
                                } else {
                                    ?>
                                                                                                                                                                                                                                                                                                                            <li data-placement="bottom" data-toggle="tooltip" title="<?php echo wp_dp_plugin_text_srt('wp_dp_map_search_map_lock'); ?>" id="top-gmap-lock-btn" class="top-gmap-lock-btn map-unloked"><i class="icon-lock_open"></i><!--<img src="<?php echo wp_dp::plugin_url() ?>assets/frontend/images/lock.svg" alt="">--></li>
                                    <?php
                                }

                                $draw_visibility = '';
                                if ( wp_is_mobile() || $wp_dp_drawing_tools == 'off' ) {
                                    $draw_visibility = ' style="visibility:hidden;"';
                                }
                                ?>
                                <li <?php echo ($draw_visibility) ?> class="map-draw-tools">
                                    <a data-placement="bottom" data-toggle="tooltip" style="<?php echo esc_html($draw_on_map_display) ?>" title="<?php echo wp_dp_plugin_text_srt('wp_dp_map_search_draw_on_map'); ?>" id="draw-map-<?php echo absint($rand_numb) ?>" class="act-btn draw-pencil-btn <?php echo esc_html($draw_on_map_disable) ?>"><i class="icon-pencil5"></i><span><?php echo wp_dp_plugin_text_srt('wp_dp_top_map_draw_btn'); ?></span></a>
                                    <a data-placement="bottom" data-toggle="tooltip" style="<?php echo esc_html($delete_on_map_display) ?>" title="<?php echo wp_dp_plugin_text_srt('wp_dp_map_search_delete_area'); ?>" id="delete-button-<?php echo absint($rand_numb) ?>" class="act-btn delete-draw-area"><i class="icon-eraser"></i><span><?php echo wp_dp_plugin_text_srt('wp_dp_top_map_clear_btn'); ?></span></a>
                                    <a data-placement="bottom" data-toggle="tooltip" style="display: none;" title="<?php echo wp_dp_plugin_text_srt('wp_dp_map_search_cancel_drawing'); ?>" id="cancel-button-<?php echo absint($rand_numb) ?>" class="act-btn delete-draw-area"><i class="icon-eraser"></i><span><?php echo wp_dp_plugin_text_srt('wp_dp_top_map_clear_btn'); ?></span></a>
                                </li>

                            </ul>
                            <div id="listing-records-<?php echo absint($rand_numb) ?>" class="listing-records-sec" style="display: none;">
                                <p>
                                    <span id="total-records-<?php echo absint($rand_numb) ?>">0</span>&nbsp;<?php echo wp_dp_plugin_text_srt('wp_dp_map_search_records_found'); ?>
                                    ,&nbsp;<?php echo wp_dp_plugin_text_srt('wp_dp_map_search_showing'); ?>&nbsp;<span id="showing-records-<?php echo absint($rand_numb) ?>">0</span>&nbsp;<?php echo wp_dp_plugin_text_srt('wp_dp_map_search_results'); ?>
                                </p>
                            </div>
                            <!-- end draw on map -->
                            <div class="wp-dp-top-gmap-holder">
                                <div class="slide-loader"></div>
                                <div class="wp-dp-ontop-gmap" id="wp-dp-ontop-gmap-<?php echo absint($rand_numb) ?>" style="height: <?php echo absint($map_map_search_height) ?>px; width:100%;"></div>
                                <div class="top-map-action-scr"><?php echo ($this->map_search_query($atts)) ?></div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="wp-dp-top-map-search<?php echo esc_html($has_map_box_class) ?>">
                            <?php
                            if ( $map_search_box_switch == 'yes' ) {
                                // ONLY FOR RANGE SLIDER
                                wp_enqueue_style('wp_dp_bootstrap_slider_css');
                                wp_enqueue_script('wp-dp-bootstrap-slider');
                                if ( $main_sections_column == 'wide' ) {
                                    echo '<div class="container">
									<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                }
                                $listing_type = isset($_GET['listing_type']) ? $_GET['listing_type'] : '';
                                $search_title = isset($_GET['search_title']) ? $_GET['search_title'] : '';
                                $search_location = isset($_GET['location']) ? $_GET['location'] : '';
                                ?>
                                <div class="top-map-search-inner<?php echo esc_html($search_box_class) ?>"> 
                                    <div class="row">
                                        <div class="main-search modern">
                                            <div class="search-default-fields">
                                                <div class="map-search-keyword-type-holder">
                                                    <?php if ( $listingsearch_title_switch == 'yes' ) { ?>
                                                        <div class="field-holder search-input">
                                                            <label>
                                                                <i class="icon-search4"></i>
                                                                <?php
                                                                $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                                                        array(
                                                                            'cust_name' => 'search_title',
                                                                            'classes' => 'input-field search_keyword',
                                                                            'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_map_search_what_looking') . '"',
                                                                            'std' => ( isset($_REQUEST['search_title']) ? $_REQUEST['search_title'] : '' ),
                                                                        )
                                                                );
                                                                ?>   
                                                            </label>
                                                        </div>
                                                        <?php
                                                    }
                                                    if ( $listingsearch_listing_type_switch == 'yes' ) {
                                                        ?>
                                                        <div class="field-holder listing-type checkbox"> 
                                                            <?php
                                                            $wp_dp_post_listing_types = new Wp_dp_Post_Listing_Types();
                                                            $listing_types_array = $wp_dp_post_listing_types->wp_dp_types_array_callback(wp_dp_plugin_text_srt('wp_dp_listing_type_meta_categories'));
                                                            if ( is_array($listing_types_array) && ! empty($listing_types_array) ) {
                                                                foreach ( $listing_types_array as $key => $value ) {
                                                                    $listing_type_slug = $key;
                                                                    break;
                                                                }
                                                            }
                                                            ?>
                                                            <ul class="dropdown-types-cont">
                                                                <li>
                                                                    <a href="javascript:void(0)" class="dropdown-types-btn"><?php echo wp_dp_plugin_text_srt('wp_dp_map_search_categories_txt') ?></a>
                                                                    <ul class="dropdown-types">
                                                                        <?php
                                                                        $number_option_flag = 1;
                                                                        foreach ( $listing_types_array as $key => $value ) {
                                                                            ?>
                                                                            <li>
                                                                                <?php
                                                                                $checked = '';
                                                                                if ( ( (isset($_REQUEST['listing_type']) && $_REQUEST['listing_type'] != '') && $_REQUEST['listing_type'] == $key ) || $listing_type_slug == $key ) {
                                                                                    $checked = 'checked="checked"';
                                                                                }
                                                                                $wp_dp_form_fields_frontend->wp_dp_form_radio_render(
                                                                                        array(
                                                                                            'simple' => true,
                                                                                            'cust_id' => 'search_form_listing_type' . $number_option_flag,
                                                                                            'cust_name' => 'listing_type',
                                                                                            'std' => $key,
                                                                                            'extra_atr' => $checked . ' onchange="wp_dp_listing_type_search_fields(this,\'' . $rand_numb . '\',\'' . $listingsearch_price_switch . '\'); wp_dp_listing_type_cate_fields(this,\'' . $rand_numb . '\',\'' . $listingsearch_categories_switch . '\'); "',
                                                                                        )
                                                                                );
                                                                                ?>
                                                                                <label for="<?php echo force_balance_tags('search_form_listing_type' . $number_option_flag) ?>"><?php echo force_balance_tags($value); ?></label>
                                                                            </li>
                                                                            <?php
                                                                            $number_option_flag ++;
                                                                        }
                                                                        ?>
                                                                    </ul>  
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <?php if ( $listingsearch_location_switch == 'yes' ) { 
                                                    global $wp_dp_plugin_options;
                                                    $radius = isset($wp_dp_plugin_options['wp_dp_default_radius_circle']) ? $wp_dp_plugin_options['wp_dp_default_radius_circle'] : '10';// temperory for displaying listing
            
                                                    
                                                    ?>
                                                <input name="radius" value="<?php echo $radius;?>" type="hidden" />
                                                <div class="field-holder search-input">
                                                        <?php
                                                        $wp_dp_select_display = 1;
                                                        wp_dp_get_custom_locations_listing_filter('<div id="wp-dp-map-search-location-select-holder" class="search-country" style="display:' . wp_dp_allow_special_char($wp_dp_select_display) . '"><div class="select-holder">', '</div></div>', false, $rand_numb, 'filter', 'maps', 'wp_dp_top_serach_trigger();');
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                                $listing_cats_array = $wp_dp_search_fields->wp_dp_listing_type_categories_options($listing_type_slug);

                                                if ( $listingsearch_categories_switch == 'yes' && ! empty($listing_cats_array) ) {
                                                    ?>
                                                    <div id="listing_type_cate_fields_<?php echo wp_dp_allow_special_char($rand_numb); ?>" class="listing-category-fields field-holder select-dropdown has-icon">
                                                        <label>
                                                            <i class="icon-home"></i>
                                                            <?php
                                                            $listing_category = ( isset($_REQUEST['listing_category']) ? $_REQUEST['listing_category'] : '' );
                                                            $listing_category = explode(',', $listing_category);
                                                            $wp_dp_opt_array = array(
                                                                'std' => $listing_category[0],
                                                                'id' => 'listing_category',
                                                                'classes' => 'chosen-select',
                                                                'cust_name' => 'listing_category',
                                                                'options' => $listing_cats_array,
                                                            );
                                                            $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                                                            ?>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                                <div class="field-holder search-btn">
                                                    <div class="search-btn-loader-<?php echo wp_dp_allow_special_char($rand_numb); ?> input-button-loader">
                                                        <?php
                                                        $zoom_level = 9;
                                                        if ( isset($_REQUEST['zoom_level']) && $_REQUEST['zoom_level'] != '' ) {
                                                            $zoom_level = $_REQUEST['zoom_level'];
                                                        } else if ( isset($wp_dp_plugin_options['wp_dp_map_zoom_level']) && $wp_dp_plugin_options['wp_dp_map_zoom_level'] != '' ) {
                                                            $zoom_level = $wp_dp_plugin_options['wp_dp_map_zoom_level'];
                                                        }
                                                        $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                                                array(
                                                                    'simple' => true,
                                                                    'cust_id' => '',
                                                                    'cust_name' => 'zoom_level',
                                                                    'std' => absint($zoom_level),
                                                                )
                                                        );
                                                        $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                                                array(
                                                                    'cust_name' => '',
                                                                    'classes' => 'bgcolor',
                                                                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_search'),
                                                                    'cust_type' => "submit",
                                                                )
                                                        );
                                                        ?>  
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            if ( $listing_type_slug != '' && $listingsearch_advance_filter_switch == 'yes' ) {

                                                $args = array(
                                                    'name' => $listing_type_slug,
                                                    'post_type' => 'listing-type',
                                                    'post_status' => 'publish',
                                                    'numberposts' => 1,
                                                );
                                                $my_posts = get_posts($args);
                                                if ( $my_posts ) {
                                                    $listing_type_id = $my_posts[0]->ID;
                                                }

                                                $wp_dp_price_minimum_options = get_post_meta($listing_type_id, 'wp_dp_price_minimum_options', true);
                                                $wp_dp_price_minimum_options = ( ! empty($wp_dp_price_minimum_options) ) ? $wp_dp_price_minimum_options : 1;
                                                $wp_dp_price_max_options = get_post_meta($listing_type_id, 'wp_dp_price_max_options', true);
                                                $wp_dp_price_max_options = ( ! empty($wp_dp_price_max_options) ) ? $wp_dp_price_max_options : 50; //50000;
                                                $wp_dp_price_interval = get_post_meta($listing_type_id, 'wp_dp_price_interval', true);
                                                $wp_dp_price_interval = ( ! empty($wp_dp_price_interval) ) ? $wp_dp_price_interval : 50;
                                                $price_type_options = array();
                                                $wp_dp_price_interval = (int) $wp_dp_price_interval;
                                                $price_counter = $wp_dp_price_minimum_options;
                                                $listing_price_array = array();
                                                // gettting all values of price
                                                $listing_price_array = wp_dp_listing_price_options($wp_dp_price_minimum_options, $wp_dp_price_max_options, $wp_dp_price_interval, wp_dp_plugin_text_srt('wp_dp_search_filter_min_price'));
                                                if ( ($listingsearch_categories_switch == 'yes' ) || ($listingsearch_price_switch == 'yes' && ! empty($listing_price_array)) || $listingsearch_advance_filter_switch == 'yes' ) {
                                                    ?>
                                                    <div id="listing_type_fields_<?php echo wp_dp_allow_special_char($rand_numb); ?>" class="search-advanced-fields">

                                                        <?php if ( $listingsearch_price_switch == 'yes' && ! empty($listing_price_array) ) { ?>
                                                            <div class="field-holder select-dropdown">
                                                                <label>
                                                                    <i class="icon-dollar"></i>
                                                                    <?php
                                                                    $wp_dp_opt_array = array(
                                                                        'std' => '',
                                                                        'id' => 'listing_price',
                                                                        'classes' => 'chosen-select',
                                                                        'cust_name' => 'listing_price',
                                                                        'options' => $listing_price_array,
                                                                    );
                                                                    $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                                                                    ?>
                                                                </label>
                                                            </div>
                                                        <?php } ?>
                                                        <?php do_action('wp_dp_listing_type_fields', $listing_type_slug); ?>
                                                        <?php do_action('wp_dp_listing_type_features', $listing_type_slug, $rand_numb); ?>
                                                        <?php
                                                        $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                                                array(
                                                                    'simple' => true,
                                                                    'cust_id' => 'advanced_search',
                                                                    'cust_name' => 'advanced_search',
                                                                    'std' => 'true',
                                                                    'classes' => '',
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <script type="text/javascript">
                                                jQuery(document).on("change", "#wp_dp_listing_category", function () {
                                                    var chngCatIntrvl = setInterval(function () {
                                                        wp_dp_top_serach_trigger();
                                                        clearInterval(chngCatIntrvl);
                                                    }, 500);
                                                });
                                                jQuery(document).on("change", "input[name='listing_type']", function () {
                                                    var chngTypIntrvl = setInterval(function () {
                                                        wp_dp_top_serach_trigger();
                                                        clearInterval(chngTypIntrvl);
                                                    }, 500);
                                                });
                                                (function ($) {
                                                    $(function () {

                                                        var search_title_old = '';
                                                        $("input[name='search_title']").blur(function () {
                                                            var search_title_new = $(this).val();
                                                            if (search_title_old != search_title_new) {
                                                                $("input[type='hidden'][name='search_title']").val(search_title_new);

                                                                wp_dp_top_serach_trigger();
                                                                search_title_old = search_title_new;
                                                            }
                                                        });
                                                        $("input[name='search_title']").keypress(function (e) {
                                                            var key = e.keyCode || e.which;
                                                            if (key == 13) {
                                                                $(this).parents("form").find("input[type='submit']").trigger('click');
                                                            }
                                                        });
                                                    });
                                                })(jQuery);
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if ( $main_sections_column == 'wide' ) {
                                    echo '
									</div>
									</div>
									</div>';
                                }
                                ?>
                                <?php
                            }
                            ?>
                        </div>
                        
                    </div>
                    <?php
                    if ( $loc_polygon != '' ) {
                        $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                array(
                                    'simple' => true,
                                    'cust_id' => "loc_polygon",
                                    'cust_name' => 'loc_polygon',
                                    'std' => $loc_polygon,
                                )
                        );
                    }
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                            array(
                                'simple' => true,
                                'cust_id' => "",
                                'cust_name' => 'ajax_filter',
                                'std' => 'true',
                            )
                    );
                    ?>
                </form>
            </div>
            <?php
            $html .= ob_get_clean();
            return $html;
        }

        function get_custom_locations() {
            global $wp_dp_plugin_options;
            $output = '<ul class="top-search-locations" style="display: none;">';
            $selected_location = '';
            $selected_item = '';
            $output .= $selected_item;
            $output .= '</ul>';
            if ( false === ( $wp_dp_location_data = wp_dp_get_transient_obj('wp_dp_location_data') ) ) {
                
            } else {
                if ( ! empty($wp_dp_location_data) ) {
                    $output .= '
					<script>
					jQuery(document).ready(function () {
						var location_data_json = \'' . str_replace("'", "", $wp_dp_location_data) . '\';
						var location_data_json_obj = JSON.parse(location_data_json);
						jQuery(".top-search-locations").html(\'\');
						jQuery.each(location_data_json_obj, function() {
							jQuery(".top-search-locations").append("<li data-val=\'"+this.value+"\'>"+this.caption+"</li>");
						});
					});
					</script>';
                }
            }
            echo wp_dp_cs_allow_special_char($output);
        }

        public function toArray($obj) {
            if ( is_object($obj) ) {
                $obj = (array) $obj;
            }
            if ( is_array($obj) ) {
                $new = array();
                foreach ( $obj as $key => $val ) {
                    $new[$key] = $this->toArray($val);
                }
            } else {
                $new = $obj;
            }

            return $new;
        }

        public function pointInPolygon($point, $polygon) {
            $return = false;
            foreach ( $polygon as $k => $p ) {
                if ( ! $k )
                    $k_prev = count($polygon) - 1;
                else
                    $k_prev = $k - 1;

                if ( ($p[1] < $point[1] && $polygon[$k_prev][1] >= $point[1] || $polygon[$k_prev][1] < $point[1] && $p[1] >= $point[1]) && ($p[0] <= $point[0] || $polygon[$k_prev][0] <= $point[0]) ) {
                    if ( $p[0] + ($point[1] - $p[1]) / ($polygon[$k_prev][1] - $p[1]) * ($polygon[$k_prev][0] - $p[0]) < $point[0] ) {
                        $return = ! $return;
                    }
                }
            }
            return $return;
        }

        public function map_search_query($atts = '') {
            global $wpdb, $wp_dp_plugin_options, $wp_dp_shortcode_listings_frontend;

            $listing_type = '';
            // getting arg array from ajax
            if ( isset($_REQUEST['atts']) && $_REQUEST['atts'] ) {
                $atts = $_REQUEST['atts'];
                $atts = json_decode(str_replace('\"', '"', $atts));
                $atts = $this->toArray($atts);
            }
            if ( isset($atts) && $atts != '' && ! empty($atts) ) {
                extract($atts);
            }

            $listing_type = isset($_REQUEST['listing_type']) ? $_REQUEST['listing_type'] : $listing_type;
            $search_title = isset($_REQUEST['search_title']) ? $_REQUEST['search_title'] : '';
            $search_location = isset($_REQUEST['location']) ? $_REQUEST['location'] : '';

            $element_filter_arr = array();
            $post_ids = array();
            $default_date_time_formate = 'd-m-Y H:i:s';
            $wp_dp_map_style = isset($wp_dp_plugin_options['wp_dp_def_map_style']) && $wp_dp_plugin_options['wp_dp_def_map_style'] != '' ? $wp_dp_plugin_options['wp_dp_def_map_style'] : '';
            $wp_dp_map_lat = isset($wp_dp_plugin_options['wp_dp_post_loc_latitude']) && $wp_dp_plugin_options['wp_dp_post_loc_latitude'] != '' ? $wp_dp_plugin_options['wp_dp_post_loc_latitude'] : '51.5';
            $wp_dp_map_long = isset($wp_dp_plugin_options['wp_dp_post_loc_longitude']) && $wp_dp_plugin_options['wp_dp_post_loc_longitude'] != '' ? $wp_dp_plugin_options['wp_dp_post_loc_longitude'] : '-0.2';
            $wp_dp_map_marker_icon = isset($wp_dp_plugin_options['wp_dp_map_marker_icon']) && $wp_dp_plugin_options['wp_dp_map_marker_icon'] != '' ? $wp_dp_plugin_options['wp_dp_map_marker_icon'] : wp_dp::plugin_url() . '/assets/frontend/images/map-marker.png';
            $wp_dp_map_cluster_icon = isset($wp_dp_plugin_options['wp_dp_map_cluster_icon']) && $wp_dp_plugin_options['wp_dp_map_cluster_icon'] != '' ? $wp_dp_plugin_options['wp_dp_map_cluster_icon'] : wp_dp::plugin_url() . '/assets/frontend/images/map-cluster.png';
            if ( $wp_dp_map_marker_icon != '' ) {
                $wp_dp_map_marker_icon = wp_get_attachment_url($wp_dp_map_marker_icon);
            } else {
                $wp_dp_map_marker_icon = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/map-marker.png');
            }
            if ( $wp_dp_map_cluster_icon != '' ) {
                $wp_dp_map_cluster_icon = wp_get_attachment_url($wp_dp_map_cluster_icon);
            } else {
                $wp_dp_map_cluster_icon = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/map-cluster.png');
            }
            $map_search_elem_lat = isset($atts['map_search_']) ? $atts['map_search_lat'] : '';
            $map_search_elem_long = isset($atts['map_search_long']) ? $atts['map_search_long'] : '';
            $map_latitude = $wp_dp_map_lat;
            $map_longitude = $wp_dp_map_long;

            if ( $map_search_elem_lat != '' ) {
                $map_latitude = $map_search_elem_lat;
            }

            if ( $map_search_elem_long != '' ) {
                $map_longitude = $map_search_elem_long;
            }
            // search location late long
            if ( isset($_REQUEST['location']) && $_REQUEST['location'] != '' && ! isset($_REQUEST['loc_polygon_path']) ) {
                $Wp_dp_Locations = new Wp_dp_Locations();
                $location_response = $Wp_dp_Locations->wp_dp_get_geolocation_latlng_callback(trim(strtolower($_REQUEST['location'])));

                $map_latitude = isset($location_response->lat) ? $location_response->lat : '';
                $map_longitude = isset($location_response->lng) ? $location_response->lng : '';
            }

            // listing shortcode logic start
            $filter_arr = array();
            $element_filter_arr = array();
            $default_date_time_formate = 'd-m-Y H:i:s';
            if ( isset($_REQUEST['listing_type']) && $_REQUEST['listing_type'] ) {
                $listing_type = $_REQUEST['listing_type'];
            }
            $element_filter_arr[] = array(
                'key' => 'wp_dp_listing_posted',
                'value' => strtotime(date($default_date_time_formate)),
                'compare' => '<=',
            );

            $element_filter_arr[] = array(
                'key' => 'wp_dp_listing_expired',
                'value' => strtotime(date($default_date_time_formate)),
                'compare' => '>=',
            );

            $element_filter_arr[] = array(
                'key' => 'wp_dp_listing_status',
                'value' => 'active',
                'compare' => '=',
            );
            // check if member not inactive
            $element_filter_arr[] = array(
                'key' => 'listing_member_status',
                'value' => 'active',
                'compare' => '=',
            );

            if ( $listing_type != '' && $listing_type != 'all' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_dp_listing_type',
                    'value' => $listing_type,
                    'compare' => '=',
                );
            }
            if ( isset($listing_price) && $listing_price != '' && $listing_price != 'all' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_dp_listing_price',
                    'value' => $listing_price,
                    'compare' => '=',
                );
            }
            // If featured listing.
            if ( isset($listing_listing_urgent) && $listing_listing_urgent == 'only-urgent' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_dp_promotion_urgent',
                    'value' => 'on',
                    'compare' => '=',
                );

                $element_filter_arr[] = array( 'relation' => 'OR',
                    array(
                        'key' => 'wp_dp_promotion_urgent_expiry',
                        'value' => date('Y-m-d'),
                        'compare' => '>',
                    ),
                    array(
                        'key' => 'wp_dp_promotion_urgent_expiry',
                        'value' => 'unlimitted',
                        'compare' => '=',
                    )
                );
            }
            if ( function_exists('wp_dp_listing_visibility_query_args') ) {
                $element_filter_arr = wp_dp_listing_visibility_query_args($element_filter_arr);
            }

            if ( isset($_REQUEST['listing_category']) && $_REQUEST['listing_category'] != '' ) {
                $element_filter_arr['tax_query'] = array(
                    array(
                        'taxonomy' => 'listing-category',
                        'field' => 'slug',
                        'terms' => $_REQUEST['listing_category']
                    )
                );
            }

            // get all arguments from getting flters
            $left_filter_arr = $wp_dp_shortcode_listings_frontend->get_filter_arg($listing_type);

            $search_features_filter = $wp_dp_shortcode_listings_frontend->listing_search_features_filter();
            if ( ! empty($search_features_filter) ) {
                $left_filter_arr[] = $search_features_filter;
            }

            $post_ids = array();
            if ( ! empty($left_filter_arr) ) {

                $meta_post_ids_arr = array();
                $listing_id_condition = '';
                if ( isset($left_filter_arr) && ! empty($left_filter_arr) ) {
                    $meta_post_ids_arr = wp_dp_get_query_whereclase_by_array($left_filter_arr);
                    // if no result found in filtration 
                    if ( empty($meta_post_ids_arr) ) {
                        $meta_post_ids_arr = array( 0 );
                    }
                    $ids = $meta_post_ids_arr != '' ? implode(",", $meta_post_ids_arr) : '0';
                    $listing_id_condition = " ID in (" . $ids . ") AND ";
                }
                $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE " . $listing_id_condition . " post_type='listings' AND post_status='publish'");
                if ( empty($post_ids) ) {
                    $post_ids = array( 0 );
                }
                $filter_arr[] = $left_filter_arr;
            }

            $all_post_ids = array();
            if ( ! empty($post_ids) ) {
                $all_post_ids[] = $post_ids;
            }


            if ( isset($_REQUEST['location']) && $_REQUEST['location'] != '' && ! isset($_REQUEST['loc_polygon_path']) ) {
                $post_ids = $wp_dp_shortcode_listings_frontend->listing_location_filter($_REQUEST['location'], $post_ids);
                if ( empty($post_ids) ) {
                    $post_ids = array( 0 );
                }
            }


            $post_ids = $wp_dp_shortcode_listings_frontend->listing_price_filter('', $post_ids);


            $all_post_ids = $post_ids;

            $listing_sort_by = 'recent'; // default value
            $listing_sort_order = 'desc';   // default value

            if ( isset($_REQUEST['sort-by']) && $_REQUEST['sort-by'] != '' ) {
                $listing_sort_by = $_REQUEST['sort-by'];
            }

            $meta_key = 'wp_dp_listing_posted';
            $qryvar_listing_sort_type = 'DESC';
            $qryvar_sort_by_column = 'meta_value_num';

            if ( $listing_sort_by == 'recent' ) {
                $meta_key = 'wp_dp_listing_posted';
                $qryvar_listing_sort_type = 'DESC';
                $qryvar_sort_by_column = 'meta_value_num';
            } elseif ( $listing_sort_by == 'alphabetical' ) {
                $qryvar_listing_sort_type = 'ASC';
                $qryvar_sort_by_column = 'post_title';
            } elseif ( $listing_sort_by == 'price' ) {
                $qryvar_listing_sort_type = 'DESC';
                $qryvar_sort_by_column = 'meta_value_num';
            }

            $args = array(
                'posts_per_page' => '-1',
                'post_type' => 'listings',
                'post_status' => 'publish',
                'meta_key' => $meta_key,
                'order' => $qryvar_listing_sort_type,
                'orderby' => $qryvar_sort_by_column,
                'fields' => 'ids', // only load ids
                'meta_query' => array(
                    $element_filter_arr,
                )
            );



            if ( isset($atts['split_map']) && $atts['split_map'] == true ) {
                $paging_var = 'listing_page';
                $args['posts_per_page'] = $atts['posts_per_page'];
                if ( ! isset($_REQUEST[$paging_var]) ) {
                    $_REQUEST[$paging_var] = '';
                }

                $args['paged'] = $_REQUEST[$paging_var];
            }
            if ( $listing_sort_by == 'price' ) {
                $args['meta_key'] = 'wp_dp_listing_price';
            }

            if ( isset($search_title) && $search_title != '' ) {

                $query_1 = get_posts(array(
                    'posts_per_page' => '-1',
                    'fields' => 'ids',
                    'post_type' => 'listings',
                    's' => $search_title,
                    'post__in' => $all_post_ids,
                ));
                
                $query_2_params = array(
                    'posts_per_page' => '-1',
                    'fields' => 'ids',
                    'post_type' => 'listings',
                    'meta_query' => array(
                        /*
                         * @ is being saved on each update post.
                         */
                        array(
                            'key' => 'search_keywords_field',
                            'value' => $search_title,
                            'compare' => 'LIKE'
                        ),
                    )
                );
                if ( ! empty($all_post_ids) ) {
                    $query_2_params['post__in'] = $all_post_ids;
                }

                $query_2 = get_posts($query_2_params);

                $all_post_ids = array_unique(array_merge($query_1, $query_2));

                $all_post_ids = empty($all_post_ids) ? array( 0 ) : $all_post_ids;
            }

            if ( ! defined('DOING_AJAX') ) {
                global $wp_dp_shortcode_split_map_frontend;
                $all_post_ids = $wp_dp_shortcode_split_map_frontend->listing_price_filter('', $all_post_ids);
            }

            if ( isset($atts['split_map']) && $atts['split_map'] == true ) {
                $element_listing_top_category = isset($atts['listing_top_category']) ? $atts['listing_top_category'] : 'no';
                $element_listing_top_category_count = isset($atts['listing_top_category_count']) ? $atts['listing_top_category_count'] : '5';
                //// Top of Category 
                if ( $element_listing_top_category == 'yes' ) {
                    $element_top_cate_filter_arr[] = array(
                        'key' => 'wp_dp_listing_posted',
                        'value' => strtotime(current_time($default_date_time_formate, 1)),
                        'compare' => '<=',
                    );

                    $element_top_cate_filter_arr[] = array(
                        'key' => 'wp_dp_listing_expired',
                        'value' => strtotime(current_time($default_date_time_formate, 1)),
                        'compare' => '>=',
                    );

                    $element_top_cate_filter_arr[] = array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'active',
                        'compare' => '=',
                    );

                    $element_top_cate_filter_arr[] = array(
                        'key' => 'wp_dp_promotion_top-categories',
                        'value' => 'on',
                        'compare' => '=',
                    );

                    $element_top_cate_filter_arr[] = array( 'relation' => 'OR',
                        array(
                            'key' => 'wp_dp_promotion_top-categories_expiry',
                            'value' => date('Y-m-d'),
                            'compare' => '>',
                        ),
                        array(
                            'key' => 'wp_dp_promotion_top-categories_expiry',
                            'value' => 'unlimitted',
                            'compare' => '=',
                        )
                    );


                    if ( isset($_REQUEST['sort-by']) && $_REQUEST['sort-by'] == 'sold' ) {
                        $element_top_cate_filter_arr[] = array(
                            'key' => 'wp_dp_listing_sold',
                            'value' => 'yes',
                            'compare' => '=',
                        );
                    }
                    if ( $listing_type != '' && $listing_type != 'all' ) {
                        $element_top_cate_filter_arr[] = array(
                            'key' => 'wp_dp_listing_type',
                            'value' => $listing_type,
                            'compare' => '=',
                        );
                    }

                    if ( function_exists('wp_dp_listing_visibility_query_args') ) {
                        $element_top_cate_filter_arr = wp_dp_listing_visibility_query_args($element_top_cate_filter_arr);
                    }

                    $listing_type_category_name = 'wp_dp_listing_category';   // category_fieldname in db and request
                    if ( isset($_REQUEST[$listing_type_category_name]) && $_REQUEST[$listing_type_category_name] != '' ) {
                        $dropdown_query_str_var_name = explode(",", $_REQUEST[$listing_type_category_name]);
                        $cate_filter_multi_arr['relation'] = 'OR';
                        foreach ( $dropdown_query_str_var_name as $query_str_var_name_key ) {
                            $cate_filter_multi_arr[] = array(
                                'key' => $listing_type_category_name,
                                'value' => serialize($query_str_var_name_key),
                                'compare' => 'LIKE',
                            );
                        }
                        if ( isset($cate_filter_multi_arr) && ! empty($cate_filter_multi_arr) ) {
                            $element_top_cate_filter_arr[] = array(
                                $cate_filter_multi_arr
                            );
                        }
                    }
                    $top_categries_args = array(
                        'posts_per_page' => $element_listing_top_category_count,
                        'post_type' => 'listings',
                        'post_status' => 'publish',
                        'meta_key' => $meta_key,
                        'order' => $qryvar_listing_sort_type,
                        'orderby' => $qryvar_sort_by_column,
                        'fields' => 'ids', // only load ids
                        'meta_query' => array(
                            $element_top_cate_filter_arr,
                        ),
                    );

                    $listing_top_categries_loop_obj = wp_dp_get_cached_obj('listing_result_cached_top_categries_loop_obj', $top_categries_args, 12, false, 'wp_query');
                }
            }



            if ( ! empty($all_post_ids) ) {
                $args['post__in'] = $all_post_ids;
            }

            if ( isset($listing_top_categries_loop_obj->posts) && is_array($listing_top_categries_loop_obj->posts) && ! empty($listing_top_categries_loop_obj->posts) ) {
                if ( ! empty($all_post_ids) ) {
                    $all_post_ids = array_diff($all_post_ids, $listing_top_categries_loop_obj->posts);
                    $args['post__in'] = $all_post_ids;
                } else {
                    $args['post__not_in'] = $listing_top_categries_loop_obj->posts;
                }
                $args['posts_per_page'] = $args['posts_per_page'] - $listing_top_categries_loop_obj->post_count;
            }


            $points_in_polygon = false;
            $polygon_path = array();
            if ( isset($_REQUEST['loc_polygon_path']) ) {
                $points_in_polygon = true;
                $polygon_path = explode('||', $_REQUEST['loc_polygon_path']);
                if ( count($polygon_path) > 0 ) {
                    array_walk($polygon_path, function(&$val) {
                        $val = explode(',', $val);
                    });
                }
            }

            // listing shortcode logic end
			
			
            $listing_loop_count = wp_dp_get_cached_obj('listing_result_cached_loop_count', $args, 12, false, 'wp_query');
            $total_showing_listing_count = 0;

            $total_listing_count = $listing_loop_count->found_posts;
            if ( isset($listing_top_categries_loop_obj) && $listing_top_categries_loop_obj->found_posts != '' && $listing_loop_count->have_posts() ) {
                $total_listing_count += $listing_top_categries_loop_obj->post_count;
            }

            $map_cords = array();

            $listing_location_options = 'city,country';
            if ( $listing_location_options != '' ) {
                $listing_location_options = explode(',', $listing_location_options);
            }

            ////////// Top of Category Listings //////////
            if ( isset($listing_top_categries_loop_obj) && $listing_top_categries_loop_obj->have_posts() && $listing_loop_count->have_posts() ):
                while ( $listing_top_categries_loop_obj->have_posts() ) : $listing_top_categries_loop_obj->the_post();
                    global $post, $wp_dp_member_profile;

                    $listing_id = $post;

                    $listing_latitude = get_post_meta($listing_id, 'wp_dp_post_loc_latitude_listing', true);
                    $listing_longitude = get_post_meta($listing_id, 'wp_dp_post_loc_longitude_listing', true);

                    if ( $points_in_polygon ) {
                        if ( ! $this->pointInPolygon(array( $listing_latitude, $listing_longitude ), $polygon_path) ) {
                            continue;
                        }
                    }

                    $Wp_dp_Locations = new Wp_dp_Locations();
                    $listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                    $listing_type_obj = get_page_by_path($listing_type, OBJECT, 'listing-type');
                    $listing_type_id = isset($listing_type_obj->ID) ? $listing_type_obj->ID : '';
                    $listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);
                    $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
                    $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);

                    $listing_marker = get_post_meta($listing_type_id, 'wp_dp_listing_type_marker_image', true);
                    $listing_marker_hover = get_post_meta($listing_type_id, 'wp_dp_listing_type_marker_hover_image', true);

                    if ( $listing_marker != '' ) {
                        $listing_marker = wp_get_attachment_url($listing_marker);
                    } else {
                        $listing_marker = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/map-marker.png');
                    }

                    if ( $listing_marker_hover != '' ) {
                        $listing_marker_hover = wp_get_attachment_url($listing_marker_hover);
                    } else {
                        $listing_marker_hover = $listing_marker;
                    }

                    $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');

                    $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                    $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                    $wp_dp_transaction_listing_reviews = get_post_meta($listing_id, 'wp_dp_transaction_listing_reviews', true);

                    $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                    $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);

                    $total_showing_listing_count ++;

                    // end checking review on in listing type

                    $wp_dp_listing_price = '';
                    if ( $wp_dp_listing_price_options == 'price' ) {
                        $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                    } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                        $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_nearby_listings_price_on_request');
                    }

                    $listing_info_img = '';
                    if ( function_exists('listing_gallery_first_image') ) {
                        $gallery_image_args = array(
                            'listing_id' => get_the_ID(),
                            'size' => 'wp_dp_media_2',
                            'class' => 'img-map-info',
                            'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg')
                        );
                        $listing_info_img = listing_gallery_first_image($gallery_image_args);
                    }

                    $listing_info_price = '';
                    if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) {
                        $listing_info_price .= '
						<span class="listing-price">
							<span class="new-price text-color">';
                        if ( $wp_dp_listing_price_options == 'on-call' ) {
                            $listing_info_price .= $wp_dp_listing_price;
                        } else {
                            $listing_info_price .= wp_dp_listing_price($listing_id, $wp_dp_listing_price);
                        }
                        $listing_info_price .= '	
							</span>
						</span>';
                    }
                    $listing_info_address = '';
                    if ( $listing_location != '' ) {
                        $listing_info_address = '<span class="info-address">' . implode(', ', $listing_location) . '</span>';
                    }

                    ob_start();
                    $favourite_label = '';
                    $favourite_label = '';
                    $figcaption_div = true;
                    $book_mark_args = array(
                        'before_label' => $favourite_label,
                        'after_label' => $favourite_label,
                        'before_icon' => '<i class="icon-heart-o"></i>',
                        'after_icon' => '<i class="icon-heart5"></i>',
                        'before_signin_callback' => true,
                    );

                    do_action('wp_dp_favourites_frontend_button', $listing_id, $book_mark_args, $figcaption_div);
                    $list_favourite = ob_get_clean();

                    $listing_urgent = '';

                    $listing_member = $wp_dp_listing_username != '' && get_the_title($wp_dp_listing_username) != '' ? '<span class="info-member">' . sprintf(wp_dp_plugin_text_srt('wp_dp_member_members_with_colan'), get_the_title($wp_dp_listing_username)) . '</span>' : '';

                    $ratings_data = array(
                        'overall_rating' => 0.0,
                        'count' => 0,
                    );

                    $ratings_data = apply_filters('reviews_ratings_data', $ratings_data, $listing_id);

                    $listing_reviews = '';
                    if ( $wp_dp_user_reviews == 'on' && $ratings_data['count'] > 0 ) {
                        $listing_reviews .= '
                            <div class="post-rating">
                                    <div class="rating-holder">
                                            <div class="rating-star">
                                                    <span class="rating-box" style="width: ' . $ratings_data['overall_rating'] . '%;"></span>
                                            </div>
                                            <span class="ratings"><span class="rating-text">' . $ratings_data['count'] . ' ' . wp_dp_plugin_text_srt('wp_dp_list_meta_reviews') . '</span></span>
                                    </div>
                            </div>';
                    }

                    if ( $listing_latitude != '' && $listing_longitude != '' ) {
                        $map_cords[] = array(
                            'lat' => $listing_latitude,
                            'long' => $listing_longitude,
                            'id' => $listing_id,
                            'title' => get_the_title($listing_id),
                            'link' => get_permalink($listing_id),
                            'img' => $listing_info_img,
                            'price' => $listing_info_price,
                            'address' => $listing_info_address,
                            'favourite' => $list_favourite,
                            'featured' => $listing_urgent,
                            'reviews' => $listing_reviews,
                            'member' => $listing_member,
                            'marker' => $listing_marker,
                            'marker_hover' => $listing_marker_hover,
                        );
                    }

                endwhile;
                wp_reset_postdata();

            endif;
            //////////



            if ( $listing_loop_count->have_posts() ):
                while ( $listing_loop_count->have_posts() ) : $listing_loop_count->the_post();
                    global $post, $wp_dp_member_profile;

                    $listing_id = $post;

                    $listing_latitude = get_post_meta($listing_id, 'wp_dp_post_loc_latitude_listing', true);
                    $listing_longitude = get_post_meta($listing_id, 'wp_dp_post_loc_longitude_listing', true);

                    if ( $points_in_polygon ) {
                        if ( ! $this->pointInPolygon(array( $listing_latitude, $listing_longitude ), $polygon_path) ) {
                            continue;
                        }
                    }

                    $Wp_dp_Locations = new Wp_dp_Locations();
                    $listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                    $listing_type_obj = get_page_by_path($listing_type, OBJECT, 'listing-type');
                    $listing_type_id = isset($listing_type_obj->ID) ? $listing_type_obj->ID : '';
                    $listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);
                    $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
                    $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);

                    $listing_marker = get_post_meta($listing_type_id, 'wp_dp_listing_type_marker_image', true);
                    $listing_marker_hover = get_post_meta($listing_type_id, 'wp_dp_listing_type_marker_hover_image', true);

                    if ( $listing_marker != '' ) {
                        $listing_marker = wp_get_attachment_url($listing_marker);
                    } else {
                        $listing_marker = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/map-marker.png');
                    }

                    if ( $listing_marker_hover != '' ) {
                        $listing_marker_hover = wp_get_attachment_url($listing_marker_hover);
                    } else {
                        $listing_marker_hover = $listing_marker;
                    }

                    $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');

                    $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                    $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                    $wp_dp_transaction_listing_reviews = get_post_meta($listing_id, 'wp_dp_transaction_listing_reviews', true);

                    $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                    $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);

                    $total_showing_listing_count ++;

                    // end checking review on in listing type

                    $wp_dp_listing_price = '';
                    if ( $wp_dp_listing_price_options == 'price' ) {
                        $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                    } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                        $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_nearby_listings_price_on_request');
                    }

                    $listing_info_img = '';
                    if ( function_exists('listing_gallery_first_image') ) {
                        $gallery_image_args = array(
                            'listing_id' => get_the_ID(),
                            'size' => 'wp_dp_media_2',
                            'class' => 'img-map-info',
                            'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg')
                        );
                        $listing_info_img = listing_gallery_first_image($gallery_image_args);
                    }

                    $listing_info_price = '';
                    if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) {
                        $listing_info_price .= '
						<span class="listing-price">
							<span class="new-price text-color">';
                        if ( $wp_dp_listing_price_options == 'on-call' ) {
                            $listing_info_price .= $wp_dp_listing_price;
                        } else {
                            $listing_info_price .= wp_dp_listing_price($listing_id, $wp_dp_listing_price);
                        }
                        $listing_info_price .= '	
							</span>
						</span>';
                    }
                    $listing_info_address = '';
                    if ( $listing_location != '' ) {
                        $listing_info_address = '<span class="info-address">' . implode(', ', $listing_location) . '</span>';
                    }

                    ob_start();
                    $favourite_label = '';
                    $favourite_label = '';
                    $figcaption_div = true;
                    $book_mark_args = array(
                        'before_label' => $favourite_label,
                        'after_label' => $favourite_label,
                        'before_icon' => '<i class="icon-heart-o"></i>',
                        'after_icon' => '<i class="icon-heart5"></i>',
                        'before_signin_callback' => true,
                    );

                    do_action('wp_dp_favourites_frontend_button', $listing_id, $book_mark_args, $figcaption_div);
                    $list_favourite = ob_get_clean();

                    $listing_urgent = '';

                    $listing_member = $wp_dp_listing_username != '' && get_the_title($wp_dp_listing_username) != '' ? '<span class="info-member">' . sprintf(wp_dp_plugin_text_srt('wp_dp_member_members_with_colan'), get_the_title($wp_dp_listing_username)) . '</span>' : '';

                    $ratings_data = array(
                        'overall_rating' => 0.0,
                        'count' => 0,
                    );

                    $ratings_data = apply_filters('reviews_ratings_data', $ratings_data, $listing_id);

                    $listing_reviews = '';
                    if ( $wp_dp_user_reviews == 'on' && $ratings_data['count'] > 0 ) {
                        $listing_reviews .= '
                            <div class="post-rating">
                                    <div class="rating-holder">
                                            <div class="rating-star">
                                                    <span class="rating-box" style="width: ' . $ratings_data['overall_rating'] . '%;"></span>
                                            </div>
                                            <span class="ratings"><span class="rating-text">' . sprintf(_nx('%s Review', '%s Reviews', $ratings_data['count'], 'number of reviews', 'wp-dp'), number_format_i18n($ratings_data['count']));
                        '</span></span>
                                    </div>
                            </div>';
                    }

                    if ( $listing_latitude != '' && $listing_longitude != '' ) {
                        $map_cords[] = array(
                            'lat' => $listing_latitude,
                            'long' => $listing_longitude,
                            'id' => $listing_id,
                            'title' => get_the_title($listing_id),
                            'link' => get_permalink($listing_id),
                            'img' => $listing_info_img,
                            'price' => $listing_info_price,
                            'address' => $listing_info_address,
                            'favourite' => $list_favourite,
                            'featured' => $listing_urgent,
                            'reviews' => $listing_reviews,
                            'member' => $listing_member,
                            'marker' => $listing_marker,
                            'marker_hover' => $listing_marker_hover,
                        );
                    }

                endwhile;
                wp_reset_postdata();

            endif;
            /*
             *  draw on map
             */
            $wp_dp_listing_strings = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'plugin_url' => wp_dp::plugin_url(),
                'draw_area' => wp_dp_plugin_text_srt('wp_dp_top_map_draw_btn'),
                'alert_name' => wp_dp_plugin_text_srt('wp_dp_alerts_alert_name_title'),
                'alert_email' => wp_dp_plugin_text_srt('wp_dp_alerts_email_address'),
                'alert_save_search' => wp_dp_plugin_text_srt('wp_dp_alerts_save_search'),
                'alert_or' => wp_dp_plugin_text_srt('wp_dp_alerts_map_or'),
                'alert_submit' => wp_dp_plugin_text_srt('wp_dp_alerts_map_submit'),
                'alert_view_prop' => wp_dp_plugin_text_srt('wp_dp_alerts_map_view_listings'),
                'map_locked' => wp_dp_plugin_text_srt('wp_dp_map_search_map_lock'),
                'map_unlocked' => wp_dp_plugin_text_srt('wp_dp_map_search_map_unlock'),
                'geoloc_timeout' => wp_dp_plugin_text_srt('wp_dp_map_geoloc_timeout'),
                'geoloc_not_support' => wp_dp_plugin_text_srt('wp_dp_map_geoloc_not_support'),
                'geoloc_unavailable' => wp_dp_plugin_text_srt('wp_dp_map_geoloc_unavailable'),
            );
            wp_localize_script('wp-dp-listing-top-map', 'wp_dp_top_gmap_strings', $wp_dp_listing_strings);
            $del_btn_class = ' is-disabled';
            $draw_btn_class = '';

            if ( isset($_REQUEST['location']) && $_REQUEST['location'] != '' && ! isset($_REQUEST['loc_polygon_path']) ) {
                $get_loc_term = get_term_by('slug', $_REQUEST['location'], 'wp_dp_locations');
                if ( isset($get_loc_term->term_id) ) {
                    $location_coordinates = get_term_meta($get_loc_term->term_id, 'location_coordinates', true);
                }
            }
            if ( isset($_REQUEST['zoom_level']) && $_REQUEST['zoom_level'] != '' ) {
                $map_zoom = $_REQUEST['zoom_level'];
            } else {
                $map_zoom = isset($wp_dp_plugin_options['wp_dp_map_zoom_level']) && $wp_dp_plugin_options['wp_dp_map_zoom_level'] != '' ? $wp_dp_plugin_options['wp_dp_map_zoom_level'] : '9';
            }
            $wp_dp_map_style = isset($wp_dp_plugin_options['wp_dp_def_map_style']) && $wp_dp_plugin_options['wp_dp_def_map_style'] != '' ? $wp_dp_plugin_options['wp_dp_def_map_style'] : '';
            $map_custom_style = isset($wp_dp_plugin_options['wp_dp_map_custom_style']) && $wp_dp_plugin_options['wp_dp_map_custom_style'] != '' ? $wp_dp_plugin_options['wp_dp_map_custom_style'] : '';
            $wp_dp_map_lat = isset($wp_dp_plugin_options['wp_dp_post_loc_latitude']) && $wp_dp_plugin_options['wp_dp_post_loc_latitude'] != '' ? $wp_dp_plugin_options['wp_dp_post_loc_latitude'] : '51.5';
            $wp_dp_map_long = isset($wp_dp_plugin_options['wp_dp_post_loc_longitude']) && $wp_dp_plugin_options['wp_dp_post_loc_longitude'] != '' ? $wp_dp_plugin_options['wp_dp_post_loc_longitude'] : '-0.2';
            $wp_dp_map_marker_icon = isset($wp_dp_plugin_options['wp_dp_map_marker_icon']) && $wp_dp_plugin_options['wp_dp_map_marker_icon'] != '' ? $wp_dp_plugin_options['wp_dp_map_marker_icon'] : wp_dp::plugin_url() . '/assets/frontend/images/map-marker.png';
            $wp_dp_map_cluster_icon = isset($wp_dp_plugin_options['wp_dp_map_cluster_icon']) && $wp_dp_plugin_options['wp_dp_map_cluster_icon'] != '' ? $wp_dp_plugin_options['wp_dp_map_cluster_icon'] : wp_dp::plugin_url() . '/assets/frontend/images/map-cluster.png';
            $drawing_tools_line_color = isset($wp_dp_plugin_options['wp_dp_drawing_tools_line_color']) && $wp_dp_plugin_options['wp_dp_drawing_tools_line_color'] != '' ? $wp_dp_plugin_options['wp_dp_drawing_tools_line_color'] : '#1e90ff';
            $drawing_tools_fill_color = isset($wp_dp_plugin_options['wp_dp_drawing_tools_fill_color']) && $wp_dp_plugin_options['wp_dp_drawing_tools_fill_color'] != '' ? $wp_dp_plugin_options['wp_dp_drawing_tools_fill_color'] : '#1e90ff';
            if ( $wp_dp_map_marker_icon != '' ) {
                $wp_dp_map_marker_icon = wp_get_attachment_url($wp_dp_map_marker_icon);
            } else {
                $wp_dp_map_marker_icon = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/map-marker.png');
            }
            if ( $wp_dp_map_cluster_icon != '' ) {
                $wp_dp_map_cluster_icon = wp_get_attachment_url($wp_dp_map_cluster_icon);
            } else {
                $wp_dp_map_cluster_icon = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/map-cluster.png');
            }

            $map_custom_style = str_replace('&quot;', '"', $map_custom_style);

            if ( isset($location_coordinates) && ! empty($location_coordinates) ) {
                $location_coordinates_arr = json_decode($location_coordinates, true);

                if ( isset($location_coordinates_arr[0]['lat']) && isset($location_coordinates_arr[0]['lng']) ) {
                    $wp_dp_map_lat = $location_coordinates_arr[0]['lat'];
                    $wp_dp_map_long = $location_coordinates_arr[0]['lng'];
                }
            }
            if ( isset($polygon_path) ) {
                $loc_poly_cords_bounds = $polygon_path;
            }
			
            // add geo location lat long
            $rand_numb = $map_search_result_page = isset($atts['rand_numb']) ? $atts['rand_numb'] : '';
			$map_params = array();
            $map_params = array(
                'map_id' => $rand_numb,
                'map_zoom' => $map_zoom,
                'latitude' => $map_latitude,
                'longitude' => $map_longitude,
                'map_style' => $wp_dp_map_style,
                'map_custom_style' => $map_custom_style,
                'map_cords' => $map_cords,
                'marker_icon' => $wp_dp_map_marker_icon,
                'cluster_icon' => $wp_dp_map_cluster_icon,
                'draw_line_color' => $drawing_tools_line_color,
                'draw_fill_color' => $drawing_tools_fill_color,
                'full_screen' => wp_dp_plugin_text_srt('wp_dp_map_full_screen_text'),
                'exit_full_screen' => wp_dp_plugin_text_srt('wp_dp_map_exit_full_screen_text'),
				'is_mobile' => wp_is_mobile() ? true : false,
            );
            if ( isset($location_coordinates) && ! empty($location_coordinates) ) {
                $map_params['location_cords'] = $location_coordinates;
                $del_btn_class = '';
                $draw_btn_class = ' is-disabled';
            }
            if ( isset($loc_poly_cords_bounds) && ! empty($loc_poly_cords_bounds) ) {
                $map_params['location_cords'] = $loc_poly_cords_bounds;
                $del_btn_class = '';
                $draw_btn_class = ' is-disabled';
            }
            $map_json = json_encode($map_params);
            ob_start();
            ?>
            <script type="text/javascript">
				
                var $total_showing_listing_count = '<?php echo esc_html($total_showing_listing_count); ?>';
                var $total_listing_count = '<?php echo esc_html($total_listing_count); ?>';
                var tota_listing_countActualLimit = 1000;
                // change tot record in lable
                if ($total_showing_listing_count >= 0) {
                    if ($total_showing_listing_count >= (tota_listing_countActualLimit - 1)) {
                        jQuery('#total-records-<?php echo esc_html($rand_numb); ?>').html(tota_listing_countActualLimit + '+');
                        jQuery('#showing-records-<?php echo esc_html($rand_numb); ?>').html(tota_listing_countActualLimit + '+');
                    } else {
                        jQuery('#total-records-<?php echo esc_html($rand_numb); ?>').html($total_listing_count);
                        jQuery('#showing-records-<?php echo esc_html($rand_numb); ?>').html($total_showing_listing_count);
                    }
                    jQuery('#listing-records-<?php echo esc_html($rand_numb); ?>').show();
                }

                var top_dataobj = jQuery.parseJSON('<?php echo addslashes($map_json) ?>');
            <?php
            if ( isset($_POST['action']) ) {
                ?>
                    all_marker = [];
                    if (reset_top_map_marker) {
                        for (var i = 0; i < reset_top_map_marker.length; i++) {
                            reset_top_map_marker[i].setMap(null);
                        }
                    }
                    if (markerClusterers) {
                        markerClusterers.clearMarkers();
                    }
                    wp_dp_listing_top_map(top_dataobj, 'true');



                <?php
            } else {
                ?>
                    jQuery(window).load(function () {
                        wp_dp_listing_top_map(top_dataobj, 'false');
                        jQuery('.top-gmap-loader').html('');
                    });
                <?php
            }
            ?>
            </script>
            <?php
            $html = ob_get_clean();

            if ( isset($_POST['action']) ) {
                $listing_type = isset($_POST['listing_type']) ? $_POST['listing_type'] : '';
                $listing_type_obj = get_page_by_path($listing_type, OBJECT, 'listing-type');
                $listing_type_id = isset($listing_type_obj->ID) ? $listing_type_obj->ID : '';
                $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
                $wp_dp_search_result_page = get_post_meta($listing_type_id, 'wp_dp_search_result_page', true);
                $wp_dp_search_result_page = ( $wp_dp_search_result_page != '' ) ? wp_dp_wpml_lang_page_permalink($wp_dp_search_result_page, 'page') : '';
                if ( $wp_dp_search_result_page == '' || $listing_type == '' ) {
                    $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
                    $wp_dp_search_result_page = ( $wp_dp_search_result_page != '' ) ? wp_dp_wpml_lang_page_permalink($wp_dp_search_result_page, 'page') : '';
                }
                echo json_encode(array( 'html' => $html, 'search_page' => $wp_dp_search_result_page ));
                die;
            } else {
                return $html;
            }
        }

        public function quick_view_content_callback() {
            $http_request = wp_dp_server_protocol();
            $listing_id = wp_dp_get_input('listing_id', '', 'STRING');
            $listings_excerpt_length = wp_dp_get_input('listings_excerpt_length', '', 'STRING');
            $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
            $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
            if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                $listing_type_id = $listing_type_post->ID;
            $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
            $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
            $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);
            $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
            $wp_dp_post_loc_address_listing = get_post_meta($listing_id, 'wp_dp_post_loc_address_listing', true);
            $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
            $wp_dp_listing_price = '';
            if ( $wp_dp_listing_price_options == 'price' ) {
                $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
            } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
            }
            $wp_dp_listing_member = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            ?>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?php
                    do_action('wp_dp_images_gallery_element_html', $listing_id);
                    ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php
                            do_action('wp_dp_map_element_html', $listing_id);
                            ?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="listing-medium">
                                <div class="text-holder">
                                    <div class="post-title">
                                        <h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo esc_html(get_the_title($listing_id)); ?></a></h4>
                                    </div>
                                    <?php
                                    $member_image_id = get_post_meta($wp_dp_listing_member, 'wp_dp_profile_image', true);
                                    $member_image = wp_get_attachment_image_src($member_image_id, 'thumbnail');
                                    if ( $member_image == '' || FALSE == get_post_status($wp_dp_listing_member) ) {
                                        $member_image[0] = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/member-no-image.jpg');
                                    }
                                    if ( ! empty($wp_dp_post_loc_address_listing) ) {
                                        ?>
                                        <ul class="listing-location">
                                            <li><span><?php echo esc_html($wp_dp_post_loc_address_listing); ?></span></li>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                    <div class="listing-price-reviews-holder">
                                        <?php
                                        if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) {
                                            ?>
                                            <span class="listing-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                                <?php
                                                if ( $wp_dp_listing_price_options == 'on-call' ) {
                                                    echo force_balance_tags($wp_dp_listing_price);
                                                } else {
                                                    
                                                    $listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price, '<span class="price" content="' . $wp_dp_listing_price . '" itemprop="price">', '</span>', '<span class="special-price" content="" itemprop="price">', '</span>');
                                                    $wp_dp_get_currency_sign = wp_dp_get_currency_sign('code');
                                                    echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_dp_get_currency_sign . '"></span>';
                                                    echo force_balance_tags($listing_info_price);
                                                }
                                                ?>
                                            </span>
                                            <?php
                                        }

                                        do_action('wp_rem_reviews_listing_ui', $listing_id, 'fancy');
                                        $reviews = apply_filters('wp_rem_reviews_with_desc_ui', $listing_id, 1);
                                        $review_des = '';
                                        $review_img = '';
                                        foreach ( $reviews as $review ) {
                                            if ( ! empty($review) ) {
                                                if ( isset($review['description']) && $review['description'] != '' ) {
                                                    $review_des = $review['description'];
                                                    $review_img = $review['img'];
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    if ( isset($review_des) && $review_des == '' ) {
                                        $list_content = get_post_field('post_content', $listing_id);
                                        echo '<p class="listing-desc"><strong>' . wp_dp_plugin_text_srt('wp_dp_listing_about') . '</strong>' . wp_dp_limit_text($list_content, $listings_excerpt_length, '...<a href="' . esc_url(get_permalink($listing_id)) . '">' . esc_html(wp_dp_plugin_text_srt('wp_dp_listing_read_mored')) . '</a>') . '</p>';
                                    } else {
                                        if ( $review_img != '' ) {
                                            ?><div class="thumb-img">
                                                <figure><img src="<?php echo esc_url($review_img); ?>" alt="" ></figure>
                                            </div>
                                        <?php }
                                        ?>
                                        <div class = "post-time">
                                            <?php if ( $wp_dp_listing_member != '' && FALSE != get_post_status($wp_dp_listing_member) ) {
                                                ?>
                                                <span><p class="listing-desc"><?php echo wp_dp_limit_text($review_des, $listings_excerpt_length, '...<a href="' . esc_url(get_permalink($listing_id)) . '">' . esc_html(wp_dp_plugin_text_srt('wp_dp_listing_read_mored')) . '</a>'); ?></p></span>
                                                <?php } ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            die();
        }

    }

    global $wp_dp_shortcode_map_search_front;
    $wp_dp_shortcode_map_search_front = new Wp_dp_Shortcode_Map_Search_front();
}
