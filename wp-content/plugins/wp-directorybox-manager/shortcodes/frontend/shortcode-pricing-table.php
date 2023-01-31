<?php
/**
 * File Type: Searchs Shortcode Frontend
 */
if ( ! class_exists('Wp_dp_Shortcode_Pricing_Table_front') ) {

    class Wp_dp_Shortcode_Pricing_Table_front {

        /**
         * Constant variables
         */
        var $PREFIX = 'pricing_table';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_dp_pricing_table_shortcode_callback' ));
            add_action('wp_ajax_wp_dp_listing_pkg_select', array( $this, 'wp_dp_listing_pkg_select_callback' ), 10);
            add_action('wp_ajax_nopriv_wp_dp_listing_pkg_select', array( $this, 'wp_dp_listing_pkg_select_callback' ), 10);
        }

        /*
         * Shortcode View on Frontend
         */

        public function wp_dp_pkgs($value = '') {
            $pkgs_options = '';
            $args = array( 'posts_per_page' => '-1', 'post_type' => 'packages', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC' );
            $cust_query = get_posts($args);
            $pkgs_options .= '<option value="">' . wp_dp_plugin_text_srt('wp_dp_select_packages') . '</option>';
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                $pkg_counter = 1;
                foreach ( $cust_query as $pkg_post ) {
                    $option_selected = '';
                    if ( $value != '' && $value == $pkg_post->ID ) {
                        $option_selected = ' selected="selected"';
                    }
                    $pkgs_options .= '<option' . $option_selected . ' value="' . $pkg_post->ID . '">' . get_the_title($pkg_post->ID) . '</option>' . "\n";
                    $pkg_counter ++;
                }
            }

            $select_field = '<select name="pt_pkg_url[]">' . $pkgs_options . '</select>';

            return $select_field;
        }

        function combine_pt_section($keys, $values) {
            $result = array();
            foreach ( $keys as $i => $k ) {
                $result[$k][] = $values[$i];
            }
            array_walk($result, function($v){ $v = (count($v) == 1)? array_pop($v): $v; });
            return $result;
        }

        public function wp_dp_pricing_table_shortcode_callback($atts, $content = "") {

            global $current_user, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            $pricing_table_id = isset($atts['wp_dp_pricing_tables']) ? $atts['wp_dp_pricing_tables'] : '';
            $pricing_table_title = isset($atts['pricing_table_title']) ? $atts['pricing_table_title'] : '';
            $pricing_tabl_subtitle = isset($atts['pricing_table_subtitle']) ? $atts['pricing_table_subtitle'] : '';
            $pricing_table_title_align = isset($atts['pricing_table_title_align']) ? $atts['pricing_table_title_align'] : '';


            $wp_dp_pricing_table_element_title_color = isset($atts['wp_dp_pricing_table_element_title_color']) ? $atts['wp_dp_pricing_table_element_title_color'] : '';
            $wp_dp_pricing_table_element_subtitle_color = isset($atts['wp_dp_pricing_table_element_subtitle_color']) ? $atts['wp_dp_pricing_table_element_subtitle_color'] : '';
            $wp_dp_pricing_plan_seperator_style = isset($atts['wp_dp_pricing_plan_seperator_style']) ? $atts['wp_dp_pricing_plan_seperator_style'] : '';

            $pricing_table_view = isset($atts['pricing_table_view']) ? $atts['pricing_table_view'] : 'simple';
            ob_start();
            $page_element_size = isset($atts['pricing_table_element_size']) ? $atts['pricing_table_element_size'] : 100;
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size, $atts) . ' ">';
            }

            $rand_numb = rand(1000000, 99999999);
            if ( isset($_POST['wp_dp_package_buy']) && $_POST['wp_dp_package_buy'] == '1' ) {

                $package_id = isset($_POST['package_id']) ? $_POST['package_id'] : 0;
                $listing_id = isset($_POST['listing_id']) ? $_POST['listing_id'] : 0;

                $wp_dp_price_table_listing_switch = get_post_meta($pricing_table_id, 'wp_dp_subscribe_action', true);


                if ( $wp_dp_price_table_listing_switch == 'listing' ) {
                    $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_create_listing_page']) ? $wp_dp_plugin_options['wp_dp_create_listing_page'] : '';
                    $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                    $redirect_form_id = rand(1000000, 9999999);
                    $redirect_html = '<form id="form-' . $redirect_form_id . '" method="get" action="' . $wp_dp_dashboard_link . '">';
                    if ( isset($listing_id) && $listing_id != 0 ) {
                        $redirect_html .= $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                array(
                                    'simple' => true,
                                    'cust_id' => '',
                                    'cust_name' => 'listing_id',
                                    'return' => true,
                                    'std' => $listing_id,
                                )
                        );
                    }
                    $redirect_html .= $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                            array(
                                'simple' => true,
                                'cust_id' => '',
                                'cust_name' => 'package_id',
                                'return' => true,
                                'std' => $package_id,
                            )
                    );
                    if ( isset($_GET['lang']) ) {
                        $redirect_html .= $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                array(
                                    'simple' => true,
                                    'cust_id' => '',
                                    'cust_name' => 'lang',
                                    'return' => true,
                                    'std' => $_GET['lang'],
                                )
                        );
                    }
                    $redirect_html .= '
						</form>
                        <script>document.getElementById("form-' . $redirect_form_id . '").submit();</script>';
                    echo force_balance_tags($redirect_html);
                } else {

                    $form_rand_numb = isset($_POST['wp_dp_package_random']) ? $_POST['wp_dp_package_random'] : '';
                    $form_rand_transient = get_transient('wp_dp_package_random');

                    if ( $form_rand_transient != $form_rand_numb ) {

                        $wp_dp_listing_obj = new wp_dp_member_listing_actions();
                        $company_id = wp_dp_company_id_form_user_id($current_user->ID);

                        set_transient('wp_dp_package_random', $form_rand_numb, 60 * 60 * 24 * 30);

                        $wp_dp_listing_obj->wp_dp_listing_add_transaction('buy_package', 0, $package_id, $company_id);
                    }
                }
            }

            $no_member_msg = '';
            if ( is_user_logged_in() && ( ! current_user_can('wp_dp_member') || ! current_user_can('administrator') ) ) {
                $no_member_msg = '
				<div id="response-' . $rand_numb . '" class="response-holder" style="display: none;">
					<div class="alert alert-warning fade in">' . wp_dp_plugin_text_srt('wp_dp_packages_become_member') . '</div>
				</div>';
            }
            if ( $pricing_table_title != '' || $pricing_tabl_subtitle != '' ) {
                $wp_dp_element_structure = '';
                $wp_dp_element_structure = wp_dp_plugin_title_sub_align($pricing_table_title, $pricing_tabl_subtitle, $pricing_table_title_align, $wp_dp_pricing_table_element_title_color, $wp_dp_pricing_plan_seperator_style, $wp_dp_pricing_table_element_subtitle_color);
                echo force_balance_tags($wp_dp_element_structure);
            }
            if ( $pricing_table_id != '' ) {

                $pt_pkg_name = get_post_meta($pricing_table_id, 'wp_dp_pt_pkg_names', true);
                $pt_pkg_price = get_post_meta($pricing_table_id, 'wp_dp_pt_pkg_prices', true);
                $pt_pkg_desc = get_post_meta($pricing_table_id, 'wp_dp_pt_pkg_descs', true);
                $pt_pkg_btn_txt = get_post_meta($pricing_table_id, 'wp_dp_pt_pkg_btn_txts', true);
                $pt_pkg_feat = get_post_meta($pricing_table_id, 'wp_dp_pt_pkg_feats', true);
                $pt_pkg_url = get_post_meta($pricing_table_id, 'wp_dp_pt_pkg_urls', true);
                $row_num_input = get_post_meta($pricing_table_id, 'wp_dp_pt_row_num', true);
                $pt_col_input = get_post_meta($pricing_table_id, 'wp_dp_pt_col_vals', true);
                $pt_col_sub_input = get_post_meta($pricing_table_id, 'wp_dp_pt_col_subs', true);
                $pt_row_title = get_post_meta($pricing_table_id, 'wp_dp_pt_row_title', true);
                $pt_pkg_dur = get_post_meta($pricing_table_id, 'wp_dp_pt_pkg_durs', true);
                $pt_pkg_color = get_post_meta($pricing_table_id, 'wp_dp_pt_pkg_color', true);
                $pt_sec_val = get_post_meta($pricing_table_id, 'wp_dp_pt_sec_vals', true);
                $pt_sec_pos = get_post_meta($pricing_table_id, 'wp_dp_pt_sec_pos', true);

                if ( is_array($pt_pkg_name) && sizeof($pt_pkg_name) > 0 ) {

                    $has_featured = ' has-featured';
                    foreach ( $pt_pkg_name as $key => $val ) {
                        $pkg_featured = isset($pt_pkg_feat[$key]) ? $pt_pkg_feat[$key] : '';
                        if ( $pkg_featured == 'yes' ) {
                            $has_featured = ' has-featured';
                        }
                    }

                    $pricing_table_class = '';
                    if ( $pricing_table_view == 'fancy' ) {
                        $pricing_table_class = 'fancy-price-plans';
                    } elseif ( $pricing_table_view == 'modern' ) {
                        $pricing_table_class = 'modern-price-plans';
                    } else {
                        $pricing_table_class = 'simple-price-plans';
                    }
                    $dyna_value = count($pt_pkg_name);
                    ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="price-plans <?php echo esc_html($pricing_table_class . $has_featured); ?>">
                                <div class="row">
                                    <?php
                                    $Wp_dp_Member_Register_User_And_Listing = new Wp_dp_Member_Register_User_And_Listing();
                                    $user_active_pkgs = $Wp_dp_Member_Register_User_And_Listing->user_all_active_pkgs_ids();
                                    if ( ! is_array($user_active_pkgs) && $user_active_pkgs == '' ) {
                                        $user_active_pkgs = array();
                                    }

                                    $counter = 1;
                                    foreach ( $pt_pkg_name as $key => $val ) {
                                        $pkg_featured = isset($pt_pkg_feat[$key]) ? $pt_pkg_feat[$key] : '';
                                        $price = isset($pt_pkg_price[$key]) ? $pt_pkg_price[$key] : '';
                                        $pkg_desc = isset($pt_pkg_desc[$key]) ? $pt_pkg_desc[$key] : '';
                                        $pkg_btn_txt = isset($pt_pkg_btn_txt[$key]) ? $pt_pkg_btn_txt[$key] : wp_dp_plugin_text_srt('wp_dp_packages_buy_now');
                                        $pkg_id = isset($pt_pkg_url[$key]) ? $pt_pkg_url[$key] : '';
                                        $pkg_dur = isset($pt_pkg_dur[$key]) ? $pt_pkg_dur[$key] : '';
                                        $pkg_colr = isset($pt_pkg_color[$key]) ? $pt_pkg_color[$key] : '';



                                        $featured_class = isset($pkg_featured) && $pkg_featured == 'yes' ? 'featured-package' : '';

                                        $already_active = false;
                                        if ( in_array($pkg_id, $user_active_pkgs) ) {
                                            $already_active = true;
                                        }
                                        ?>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <div class="price-post <?php echo esc_html($featured_class); ?>">
                                                <div class="text-holder">
                                                    <?php
                                                    $this->wp_dp_price_table_header($pricing_table_view, $val, $pkg_featured, $price, $pkg_dur, $pkg_desc, $counter, $already_active, $pkg_colr);
                                                    ?>
                                                    <div class="price-body">
                                                        <div class="list-holder">
                                                            <ul class="price-list">
                                                                <?php
                                                                $row_count = 0;
                                                                for ( $i = $key; $i < count($pt_col_input); $i = $i + $dyna_value ) {
                                                                    $row_title = isset($pt_row_title[$row_count]) ? $pt_row_title[$row_count] : '';
                                                                    $col_input = isset($pt_col_input[$i]) ? $pt_col_input[$i] : '';
                                                                    $col_sub_input = isset($pt_col_sub_input[$i]) ? $pt_col_sub_input[$i] : '';
								    $col_sub_input = isset($pt_col_sub_input[$i]) ? $pt_col_sub_input[$i] : '';

                                                                    $li_class = 'list-unchecked';
                                                                    if ( $col_input != '' ) {
                                                                        $li_class = 'list-checked';
                                                                    }
                                                                    ?>
                                                                    <li class="<?php echo esc_html($li_class); ?>">
                                                                        <?php if ( $col_sub_input != '' ) { ?>
                                                                            <i class="<?php echo esc_html($col_sub_input); ?>"></i>
                                                                        <?php } ?>
                                                                        <?php if ( $row_title != '' ) { ?>
                                                                            <?php echo esc_html($row_title); ?>
                                                                        <?php } ?>
                                                                        <?php if ( $col_input != '' ) { ?>
                                                                            <?php echo ' : ' . esc_html($col_input); ?>
                                                                        <?php } ?>
                                                                    </li>
                                                                    <?php $row_count ++; ?>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <?php $this->wp_dp_price_table_footer($pricing_table_view, $rand_numb, $pkg_id, $pkg_btn_txt, $user_active_pkgs, $already_active); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $counter ++; ?>
                                    <?php } ?>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <?php
                }
            }

            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }
            $html = ob_get_clean();
            return $html;
        }

        ////
        public function wp_dp_price_table_header($view = '', $pkg_name = '', $pkg_featured = '', $price = '', $pkg_dur = '', $pkg_desc = '', $counter = 1, $already_active = false, $pkg_color = '') {


            $packg_color_style = '';
            if ( isset($pkg_color) && ! empty($pkg_color) ) {
                $packg_color_style = ' style="color:' . $pkg_color . ' !important;"';
            }


            if ( $pkg_featured == 'yes' && $view != 'fancy' ) {
                ?>
                <span class="featured"><?php echo wp_dp_plugin_text_srt('wp_dp_price_plan_most_popular'); ?></span>
            <?php } ?>
            <div class="price-header">
                <div class="price-heading">

                    <?php if ( $view == 'fancy' ) { ?>
                        <strong class="counter">
                            <span<?php echo ($packg_color_style); ?>>
                                <?php
                                if ( $counter < 10 ) {
                                    echo 0;
                                }
                                echo absint($counter);
                                ?>
                            </span>
                        </strong>
                    <?php } elseif ( $price != '' || ($view == 'modern' && $pkg_dur != '') ) { ?>
                        <strong>
                            <?php if ( is_numeric($price) && $price > 0 ) { ?>
                                <sup<?php echo ($packg_color_style); ?>><?php echo wp_dp_get_currency_sign(); ?></sup>
                                <span<?php echo ($packg_color_style); ?>><?php echo wp_dp_get_currency($price, false); ?></span>
                            <?php } else if ( is_numeric($price) ) { ?>
                                <span<?php echo ($packg_color_style); ?>><?php echo wp_dp_plugin_text_srt('wp_dp_package_type_free'); ?></span>
                            <?php } else { ?>
                                <span<?php echo ($packg_color_style); ?>><?php echo esc_html($price); ?></span>
                            <?php } ?>
                            <?php if ( $pkg_dur != '' && $view == 'modern' ) { ?>
                                <small<?php echo ($packg_color_style); ?>><?php echo esc_html($pkg_dur); ?></small>
                            <?php } ?>
                        </strong>
                    <?php } ?>
                    <?php if ( $pkg_name ) {
                        ?>
                        <span class="price-title"<?php echo ($packg_color_style); ?>><?php echo esc_html($pkg_name); ?></span>
                    <?php } ?>
                </div>
                <?php if ( $already_active == true && $view == 'simple' ) { ?>
                    <span class="already-active"><?php echo wp_dp_plugin_text_srt('wp_dp_package_already_activated'); ?></span>
                <?php } ?>

                <?php if ( $pkg_desc != '' || ( $view == 'fancy' && ($price != '' || $pkg_dur != '' ) ) ) { ?>
                    <div class="price-description"> 

                        <?php if ( $view == 'fancy' && ($price != '' || $pkg_dur != '' ) ) { ?>
                            <strong>
                                <?php if ( is_numeric($price) && $price > 0 ) { ?>
                                    <sup<?php echo ($packg_color_style); ?>><?php echo wp_dp_get_currency_sign(); ?></sup>
                                    <span<?php echo ($packg_color_style); ?>><?php echo wp_dp_get_currency($price, false); ?></span>
                                <?php } else if ( is_numeric($price) ) { ?>
                                    <span<?php echo ($packg_color_style); ?>><?php echo wp_dp_plugin_text_srt('wp_dp_package_type_free'); ?></span>
                                <?php } else { ?>
                                    <span<?php echo ($packg_color_style); ?>><?php echo esc_html($price); ?></span>
                                <?php } ?>
                                <?php if ( $pkg_dur != '' ) { ?>
                                    <small<?php echo ($packg_color_style); ?>><?php echo esc_html($pkg_dur); ?></small>
                                <?php } ?>
                            </strong>
                        <?php } ?>

                        <span class="price-detail"<?php echo ($packg_color_style); ?>><?php echo esc_html($pkg_desc); ?></span>
                        <?php if ( $already_active == true && $view == 'modern' ) { ?>
                            <span class="already-active"><?php echo wp_dp_plugin_text_srt('wp_dp_package_already_activated'); ?></span>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if ( $already_active == true && $view == 'fancy' ) { ?>
                    <span class="already-active"><?php echo wp_dp_plugin_text_srt('wp_dp_package_already_activated'); ?></span>
                <?php } ?>

            </div>
            <?php
        }

        public function wp_dp_price_table_footer($pricing_table_view = '', $rand_numb = '', $pkg_id = '', $pkg_btn_txt = '', $user_active_pkgs = array(), $already_active = false) {
            global $wp_dp_form_fields_frontend;
            ?>
            <div class="price-footer">
                <?php
                ?>
                <form method="post" id="packages-form-<?php echo absint($pkg_id); ?>">
                    <?php
                    $get_listing_id = wp_dp_get_input('listing_id', 0);
                    if ( $get_listing_id != 0 ) {
                        $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                array(
                                    'simple' => true,
                                    'cust_id' => '',
                                    'cust_name' => 'listing_id',
                                    'std' => $get_listing_id,
                                )
                        );
                    }
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                            array(
                                'simple' => true,
                                'cust_id' => '',
                                'cust_name' => 'wp_dp_package_buy',
                                'std' => '1',
                            )
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                            array(
                                'simple' => true,
                                'cust_id' => '',
                                'cust_name' => 'wp_dp_package_random',
                                'std' => absint($rand_numb),
                            )
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                            array(
                                'simple' => true,
                                'cust_id' => '',
                                'cust_name' => 'package_id',
                                'std' => absint($pkg_id),
                            )
                    );
                    if ( $already_active == true ) {
                        $pkg_btn_txt = wp_dp_plugin_text_srt('wp_dp_continue_label');
                    }
                    ?>
                    <button class="price-btn listing-pkg-select" type="button" data-id="<?php echo absint($pkg_id); ?>"><?php echo esc_html($pkg_btn_txt); ?></button>
                </form>
            </div>
            <?php
        }

        /*
         * Selecting Package
         */

        public function wp_dp_listing_pkg_select_callback() {
            global $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            $package_id = isset($_POST['package_id']) ? $_POST['package_id'] : '';
            $listing_id = wp_dp_get_input('listing_id', 0);

            $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_create_listing_page']) ? $wp_dp_plugin_options['wp_dp_create_listing_page'] : '';
            $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';

            if ( isset($listing_id) && $listing_id != 0 ) {
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $wp_dp_listing_update_url = add_query_arg(array( 'dashboard' => 'add_listing', 'listing_id' => $listing_id, 'package_id' => $package_id ), $wp_dp_dashboard_link);
                $redirect_html = '<script>location.href="' . $wp_dp_listing_update_url . '";</script>';
                echo force_balance_tags($redirect_html);
            } else {
                $redirect_form_id = rand(1000000, 9999999);
                $redirect_html = '<form id="form-' . $redirect_form_id . '" method="get" action="' . $wp_dp_dashboard_link . '">';
                $redirect_html .= $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                        array(
                            'simple' => true,
                            'cust_id' => '',
                            'cust_name' => 'package_id',
                            'return' => true,
                            'std' => $package_id,
                        )
                );
                if ( isset($_GET['lang']) ) {
                    $redirect_html .= $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                            array(
                                'simple' => true,
                                'cust_id' => '',
                                'cust_name' => 'lang',
                                'return' => true,
                                'std' => $_GET['lang'],
                            )
                    );
                }
                $redirect_html .= '</form>';
                $redirect_html .= '<script>document.getElementById("form-' . $redirect_form_id . '").submit();</script>';
                echo force_balance_tags($redirect_html);
            }
        }

    }

    global $wp_dp_shortcode_pricing_table_front;
    $wp_dp_shortcode_pricing_table_front = new Wp_dp_Shortcode_Pricing_Table_front();
}