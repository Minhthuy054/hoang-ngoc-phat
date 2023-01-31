<?php
/**
 * File Type: Opening Hours Page Element
 */
if (!class_exists('wp_dp_opening_hours_element')) {

    class wp_dp_opening_hours_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_filter('wp_dp_today_status_element_html', array($this, 'wp_dp_today_status_element_html_callback'), 11, 1);
            add_action('wp_dp_opening_hours_element_html', array($this, 'wp_dp_opening_hours_element_html_callback'), 11, 2);
            add_action('wp_dp_opening_hours_element_opened_html', array($this, 'wp_dp_opening_hours_element_opened_html_callback'), 11, 1);
            add_action('wp_dp_listing_opening_hours_element_html', array($this, 'wp_dp_listing_opening_hours_element_html_callback'), 11, 1);
            add_action('wp_dp_off_days_element_html', array($this, 'wp_dp_off_days_element_html_callback'), 11, 2);
        }

        /*
         * check today day status
         */

        public function wp_dp_today_status_element_html_callback($post_id) {
            global $wp_dp_plugin_options;
            $html = '';
            $wp_dp_opening_hours_switch = isset($wp_dp_plugin_options['wp_dp_opening_hours_switch']) ? $wp_dp_plugin_options['wp_dp_opening_hours_switch'] : 'on';
            
            if( $wp_dp_opening_hours_switch == 'off'){
                return 'opening_hours_off';
            }
            
            $html = '';
            $opening_hours_list = get_post_meta($post_id, 'wp_dp_opening_hour', true);



            $open_flag = false;
            if (isset($opening_hours_list) && !empty($opening_hours_list) && is_array($opening_hours_list)) {
                $current_day = strtolower(date('l'));
                $current_time = date('h:i a', strtotime('+1 hour'));
                $date1 = DateTime::createFromFormat('H:i a', $current_time);
                $date2 = DateTime::createFromFormat('H:i a', $opening_hours_list[$current_day]['opening_time']);
                $date3 = DateTime::createFromFormat('H:i a', $opening_hours_list[$current_day]['closing_time']);

                if ($opening_hours_list[$current_day]['day_status'] != 'on') {
                    $open_flag = false;
                } else if ($date1 >= $date2 && $date1 <= $date3) {
                    $open_flag = true;
                } else {
                    $open_flag = false;
                }
            }
            return $open_flag;
        }

        /*
         * Output features html for frontend on listing detail page.
         */

        public function wp_dp_opening_hours_element_html_callback($post_id, $op_view = '') {
            global $wp_dp_plugin_options;
            $html = '';
            $wp_dp_opening_hours_switch = isset($wp_dp_plugin_options['wp_dp_opening_hours_switch']) ? $wp_dp_plugin_options['wp_dp_opening_hours_switch'] : 'on';
            
            if( $wp_dp_opening_hours_switch == 'on'){
                $listing_type_slug = get_post_meta($post_id, 'wp_dp_listing_type', true);
                $listing_type_post = get_posts(array('posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish'));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                $wp_dp_full_data = get_post_meta($listing_type_id, 'wp_dp_full_data', true);



                $opening_hours_list = get_post_meta($post_id, 'wp_dp_opening_hour', true);

                if (isset($opening_hours_list) && !empty($opening_hours_list) && is_array($opening_hours_list)) {
                    $current_day = strtolower(date('l'));
                    $current_close = false;
                    $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_open');
                    $closed_flag = false;
                    $current_time = date('H:i a');
                    $date1 = DateTime::createFromFormat('H:i a', $current_time);
                    $date2 = DateTime::createFromFormat('H:i a', $opening_hours_list[$current_day]['opening_time']);
                    $date3 = DateTime::createFromFormat('H:i a', $opening_hours_list[$current_day]['closing_time']);

                    if ($opening_hours_list[$current_day]['day_status'] != 'on') {
                        $current_close = true;
                        $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_today_closed');
                        $closed_flag = true;
                    } else if ($date1 >= $date2 && $date1 <= $date3) {
                        $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_today') . ' :';
                    } else {
                        $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_today') . ' :';
                        $closed_flag = true;
                    }
                    $style = ( $op_view == 'listings_hours' ) ? '' : ' style="display: none;"';
                    ?>
                    <ul>
                        <li>
                            <a href="javascript:;" class="reviews-sortby-active active">
                                <?php
                                if ($op_view != 'listing-v5') {
                                    ?>
                                    <i class="icon-clock3"></i>
                                    <?php
                                }
                                ?>
                                <span><?php echo esc_html($current_day_text); ?></span>
                                <?php if ($current_close != true) { ?>
                                    <?php wp_dp_plugin_text_srt('wp_dp_opening_hours_at_opens_at'); ?> <?php echo date_i18n('g:i a', strtotime($opening_hours_list[$current_day]['opening_time'])); ?> - <?php echo date_i18n('g:i a', strtotime($opening_hours_list[$current_day]['closing_time'])); ?>
                                <?php } ?>
                            </a>
                            <ul class="delivery-dropdown"<?php echo $style; ?>>
                                <?php
                                $days_name = array(
                                    'monday' => wp_dp_plugin_text_srt('wp_dp_member_monday'),
                                    'tuesday' => wp_dp_plugin_text_srt('wp_dp_member_tuesday'),
                                    'wednesday' => wp_dp_plugin_text_srt('wp_dp_member_wednesday'),
                                    'thursday' => wp_dp_plugin_text_srt('wp_dp_member_thursday'),
                                    'friday' => wp_dp_plugin_text_srt('wp_dp_member_friday'),
                                    'saturday' => wp_dp_plugin_text_srt('wp_dp_member_saturday'),
                                    'sunday' => wp_dp_plugin_text_srt('wp_dp_member_sunday'),
                                );
                                foreach ($opening_hours_list as $opening_hours_single_day_var => $opening_hours_single_day_val) {
                                    $opening_hours_single_day_var = isset($days_name[$opening_hours_single_day_var]) ? $days_name[$opening_hours_single_day_var] : $opening_hours_single_day_var;
                                    $opening_hours_single_day_var = substr($opening_hours_single_day_var, 0, 3);
                                    if ($opening_hours_single_day_val['day_status'] == 'on') {
                                        ?>
                                        <li><a href="javascript:void(0)"><span class="opend-day"><?php echo strtoupper(esc_html($opening_hours_single_day_var)) ?></span> <span class="opend-time"><small>:</small> <?php wp_dp_plugin_text_srt('wp_dp_opening_hours_opens_at'); ?> <?php echo date_i18n('g:i a', strtotime($opening_hours_single_day_val['opening_time'])); ?> - <?php echo date_i18n('g:i a', strtotime($opening_hours_single_day_val['closing_time'])); ?></span></a></li>
                                        <?php
                                    } else {
                                        ?>
                                        <li><a href="javascript:void(0)"><span class="opend-day"><?php echo strtoupper(esc_html($opening_hours_single_day_var)) ?></span> <span class="close-day"><small>:</small> <?php echo wp_dp_plugin_text_srt('wp_dp_opening_hours_closed'); ?></span></a></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                    <?php
                }
            }

            echo force_balance_tags($html);
        }

        /*
         * Output features html for frontend on member detail page.
         */

        public function wp_dp_opening_hours_element_opened_html_callback($post_id) {
            $listing_type_slug = get_post_meta($post_id, 'wp_dp_listing_type', true);
            $listing_type_post = get_posts(array('posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish'));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $wp_dp_full_data = get_post_meta($listing_type_id, 'wp_dp_full_data', true);

            $html = '';

            $opening_hours_list = get_post_meta($post_id, 'wp_dp_opening_hour', true);

            if (isset($opening_hours_list) && !empty($opening_hours_list) && is_array($opening_hours_list)) {
                $current_day = strtolower(date('l'));
                $current_close = false;
                $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_open');
                $closed_flag = false;
                $current_time = date('H:i a');
                $date1 = DateTime::createFromFormat('H:i a', $current_time);
                $date2 = DateTime::createFromFormat('H:i a', $opening_hours_list[$current_day]['opening_time']);
                $date3 = DateTime::createFromFormat('H:i a', $opening_hours_list[$current_day]['closing_time']);

                if ($opening_hours_list[$current_day]['day_status'] != 'on') {
                    $current_close = true;
                    $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_today_closed');
                    $closed_flag = true;
                } else if ($date1 >= $date2 && $date1 <= $date3) {
                    $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_today') . ' :';
                } else {
                    $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_today') . ' :';
                    $closed_flag = true;
                }
                ?>
                <div class="field-select-holder member-opening-hours">
                    <h5><?php echo wp_dp_plugin_text_srt('wp_dp_opening_hours_opening_timings'); ?></h5>
                    <ul>
                        <li>
                            <ul class="delivery-dropdown">
                                <?php
                                $days_name = array(
                                    'monday' => wp_dp_plugin_text_srt('wp_dp_member_monday'),
                                    'tuesday' => wp_dp_plugin_text_srt('wp_dp_member_tuesday'),
                                    'wednesday' => wp_dp_plugin_text_srt('wp_dp_member_wednesday'),
                                    'thursday' => wp_dp_plugin_text_srt('wp_dp_member_thursday'),
                                    'friday' => wp_dp_plugin_text_srt('wp_dp_member_friday'),
                                    'saturday' => wp_dp_plugin_text_srt('wp_dp_member_saturday'),
                                    'sunday' => wp_dp_plugin_text_srt('wp_dp_member_sunday'),
                                );
                                foreach ($opening_hours_list as $opening_hours_single_day_var => $opening_hours_single_day_val) {
                                    $opening_hours_single_day_var = isset($days_name[$opening_hours_single_day_var]) ? $days_name[$opening_hours_single_day_var] : $opening_hours_single_day_var;
                                    if ($opening_hours_single_day_val['day_status'] == 'on') {
                                        ?>
                                        <li class="<?php echo (strtoupper(date('D', current_time('timestamp', 1))) == strtoupper($opening_hours_single_day_var) ? 'today' : ''); ?>"><a href="#"><span class="opend-day"><?php echo strtoupper(esc_html($opening_hours_single_day_var)) ?></span> <span class="opend-time"><small>:</small> <?php wp_dp_plugin_text_srt('wp_dp_opening_hours_opens_at'); ?> <?php echo date_i18n('g:i a', strtotime($opening_hours_single_day_val['opening_time'])); ?> - <?php echo date_i18n('g:i a', strtotime($opening_hours_single_day_val['closing_time'])); ?></span></a></li>
                                        <?php
                                    } else {
                                        ?>
                                        <li class="<?php echo (strtoupper(date('D', current_time('timestamp', 1))) == strtoupper($opening_hours_single_day_var) ? 'today' : ''); ?>"><a href="javascript:void(0)"><span class="opend-day"><?php echo strtoupper(esc_html($opening_hours_single_day_var)) ?></span> <span class="close-day"><small>:</small><?php echo wp_dp_plugin_text_srt('wp_dp_opening_hours_closed'); ?></span></a></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </div>
                <?php
            }

            echo force_balance_tags($html);
        }

        public function wp_dp_listing_opening_hours_element_html_callback($post_id) {
            $html = '';

            $sidebar_opening_hours = wp_dp_element_hide_show($post_id, 'sidebar_opening_hours');

            $selected_type = get_post_meta($post_id, 'wp_dp_listing_type', true);
            if ($post = get_page_by_path($selected_type, OBJECT, 'listing-type')) {
                $listing_type_id = $post->ID;
            } else {
                $listing_type_id = 0;
            }
            $opening_hours_element = get_post_meta($listing_type_id, 'wp_dp_opening_hours_element', true);
            if ($sidebar_opening_hours == 'on') {
                $opening_hours_list = get_post_meta($post_id, 'wp_dp_opening_hour', true);
                if (isset($opening_hours_list) && !empty($opening_hours_list) && is_array($opening_hours_list)) {
                    $current_day = strtolower(date('l'));
                    $current_close = false;
                    $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_open');
                    $closed_flag = false;
                    $current_time = date('H:i a');
                    $date1 = DateTime::createFromFormat('H:i a', $current_time);
                    $date2 = DateTime::createFromFormat('H:i a', $opening_hours_list[$current_day]['opening_time']);
                    $date3 = DateTime::createFromFormat('H:i a', $opening_hours_list[$current_day]['closing_time']);

                    if ($opening_hours_list[$current_day]['day_status'] != 'on') {
                        $current_close = true;
                        $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_today_closed');
                        $closed_flag = true;
                    } else if ($date1 >= $date2 && $date1 <= $date3) {
                        $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_today') . ' :';
                    } else {
                        $current_day_text = wp_dp_plugin_text_srt('wp_dp_opening_hours_today') . ' :';
                        $closed_flag = true;
                    }
                    ?>
                    <div class="listing-opening-hours">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_opening_hours_opening_timings'); ?></h5>
                        <ul>
                            <li>
                                <ul class="delivery-dropdown">
                                    <?php
                                    $days_name = array(
                                        'monday' => wp_dp_plugin_text_srt('wp_dp_member_monday'),
                                        'tuesday' => wp_dp_plugin_text_srt('wp_dp_member_tuesday'),
                                        'wednesday' => wp_dp_plugin_text_srt('wp_dp_member_wednesday'),
                                        'thursday' => wp_dp_plugin_text_srt('wp_dp_member_thursday'),
                                        'friday' => wp_dp_plugin_text_srt('wp_dp_member_friday'),
                                        'saturday' => wp_dp_plugin_text_srt('wp_dp_member_saturday'),
                                        'sunday' => wp_dp_plugin_text_srt('wp_dp_member_sunday'),
                                    );
                                    foreach ($opening_hours_list as $opening_hours_single_day_var => $opening_hours_single_day_val) {
                                        $opening_hours_single_day_var = isset($days_name[$opening_hours_single_day_var]) ? $days_name[$opening_hours_single_day_var] : $opening_hours_single_day_var;
                                        if ($opening_hours_single_day_val['day_status'] == 'on') {
                                            ?>
                                            <li class="<?php echo (strtoupper(date('D', current_time('timestamp', 1))) == strtoupper($opening_hours_single_day_var) ? 'today' : ''); ?>"><a href="javascript:void(0);"><span class="opend-day"><?php echo strtoupper(esc_html($opening_hours_single_day_var)) ?></span> <span class="opend-time"><small>:</small> <?php wp_dp_plugin_text_srt('wp_dp_opening_hours_opens_at'); ?> <?php echo date_i18n('g:i a', strtotime($opening_hours_single_day_val['opening_time'])); ?> - <?php echo date_i18n('g:i a', strtotime($opening_hours_single_day_val['closing_time'])); ?></span></a></li>
                                            <?php
                                        } else {
                                            ?>
                                            <li class="<?php echo (strtoupper(date('D', current_time('timestamp', 1))) == strtoupper($opening_hours_single_day_var) ? 'today' : ''); ?>"><a href="javascript:void(0);"><span class="opend-day"><?php echo strtoupper(esc_html($opening_hours_single_day_var)) ?></span> <span class="close-day"><small>:</small><?php echo wp_dp_plugin_text_srt('wp_dp_opening_hours_closed'); ?></span></a></li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
                echo force_balance_tags($html);
            }
        }

        public function wp_dp_off_days_element_html_callback($post_id, $op_view = '') {
            $html = '';

            $sidebar_opening_hours = wp_dp_element_hide_show($post_id, 'sidebar_opening_hours');

            $selected_type = get_post_meta($post_id, 'wp_dp_listing_type', true);
            if ($post = get_page_by_path($selected_type, OBJECT, 'listing-type')) {
                $listing_type_id = $post->ID;
            } else {
                $listing_type_id = 0;
            }
            $opening_hours_element = get_post_meta($listing_type_id, 'wp_dp_opening_hours_element', true);

            if ($sidebar_opening_hours == 'on') {
                $listing_off_days = get_post_meta($post_id, 'wp_dp_calendar', true);
                if (isset($listing_off_days) && !empty($listing_off_days) && is_array($listing_off_days)) {
                    ?>
                    <div class="listing-opening-hours">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_list_meta_off_days'); ?></h5>
                        <ul>
                            <li>
                                <ul class="delivery-dropdown">
                                    <?php
                                    foreach ($listing_off_days as $listing_off_day) {
                                        $formated_off_date_day = date_i18n("l", strtotime($listing_off_day));
                                        $formated_off_date = date_i18n(get_option('date_format'), strtotime($listing_off_day));
                                        ?>
                                        <li class=""><a href="javascript:void(0);"><span class="opend-day"><?php echo wp_dp_cs_allow_special_char($formated_off_date_day) ?></span> <span class="opend-time"><?php echo wp_dp_cs_allow_special_char($formated_off_date); ?></span></a></li>
                                                <?php
                                            }
                                            ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
                echo force_balance_tags($html);
            }
        }

    }

    global $wp_dp_opening_hours_element;
    $wp_dp_opening_hours_element = new wp_dp_opening_hours_element();
}