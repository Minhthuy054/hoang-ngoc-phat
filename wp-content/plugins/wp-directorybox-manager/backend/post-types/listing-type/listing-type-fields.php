<?php
/**
 * Listing Type custom 
 * dynamic Fields
 */
if (!class_exists('Wp_dp_Listing_Type_Dynamic_Fields')) {

    class Wp_dp_Listing_Type_Dynamic_Fields {

        public function __construct() {
            add_action('wp_ajax_wp_dp_listing_type_pb_text', array($this, 'wp_dp_listing_type_pb_text'));
            add_action('wp_ajax_wp_dp_listing_type_pb_number', array($this, 'wp_dp_listing_type_pb_number'));
            add_action('wp_ajax_wp_dp_listing_type_pb_textarea', array($this, 'wp_dp_listing_type_pb_textarea'));
            add_action('wp_ajax_wp_dp_listing_type_pb_dropdown', array($this, 'wp_dp_listing_type_pb_dropdown'));
            add_action('wp_ajax_wp_dp_listing_type_pb_date', array($this, 'wp_dp_listing_type_pb_date'));
            add_action('wp_ajax_wp_dp_listing_type_pb_email', array($this, 'wp_dp_listing_type_pb_email'));
            add_action('wp_ajax_wp_dp_listing_type_pb_url', array($this, 'wp_dp_listing_type_pb_url'));
            add_action('wp_ajax_wp_dp_listing_type_pb_range', array($this, 'wp_dp_listing_type_pb_range'));
            add_action('wp_ajax_wp_dp_listing_type_pb_section', array($this, 'wp_dp_listing_type_pb_section'));
            add_action('wp_ajax_wp_dp_check_fields_avail', array($this, 'wp_dp_check_fields_avail'));
        }

        function custom_fields() {
            global $post, $wp_dp_count_node, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text,$wp_dp_form_fields;
            $rand_f_counter = rand(1000000, 99999999);
            ?>
            <div class="inside-tab-content">
                <div class="dragitem">
                    <h4><?php echo wp_dp_plugin_text_srt('wp_dp_click_to_add_item'); ?></h4>
                    <div class="pb-form-buttons">
                        <ul>
                            <li id="field-text-<?php echo absint($rand_f_counter) ?>"><a href="javascript:wp_dp_listing_type_field_add('wp_dp_listing_type_pb_text', 'text', '<?php echo absint($rand_f_counter) ?>')" title="Text" data-type="text" data-name="custom_text"><i class="icon-new-message"></i><?php echo wp_dp_plugin_text_srt('wp_dp_text'); ?>&nbsp;&nbsp;<span></span></a></li>
                            <li id="field-number-<?php echo absint($rand_f_counter) ?>"><a href="javascript:wp_dp_listing_type_field_add('wp_dp_listing_type_pb_number', 'number', '<?php echo absint($rand_f_counter) ?>')" title="Text" data-type="number" data-name="custom_number"><i class="icon-file-text"></i><?php echo wp_dp_plugin_text_srt('wp_dp_number'); ?>&nbsp;&nbsp;<span></span></a></li>
                            <li id="field-textarea-<?php echo absint($rand_f_counter) ?>"><a href="javascript:wp_dp_listing_type_field_add('wp_dp_listing_type_pb_textarea', 'textarea', '<?php echo absint($rand_f_counter) ?>')" title="Textarea" data-type="textarea" data-name="custom_textarea"><i class="icon-message"></i><?php echo wp_dp_plugin_text_srt('wp_dp_textarea'); ?>&nbsp;&nbsp;<span></span></a></li>
                            <li id="field-select-<?php echo absint($rand_f_counter) ?>"><a href="javascript:wp_dp_listing_type_field_add('wp_dp_listing_type_pb_dropdown', 'select', '<?php echo absint($rand_f_counter) ?>')" title="Dropdown" data-type="select" data-name="custom_select"><i class="icon-arrow-down"></i><?php echo wp_dp_plugin_text_srt('wp_dp_dropdown'); ?>&nbsp;&nbsp;<span></span></a></li>
                            <li id="field-date-<?php echo absint($rand_f_counter) ?>"><a href="javascript:wp_dp_listing_type_field_add('wp_dp_listing_type_pb_date', 'date', '<?php echo absint($rand_f_counter) ?>')" title="Date" data-type="date" data-name="custom_date"><i class="icon-perm_contact_calendar"></i><?php echo wp_dp_plugin_text_srt('wp_dp_date'); ?>&nbsp;&nbsp;<span></span></a></li>
                            <li id="field-email-<?php echo absint($rand_f_counter) ?>"><a href="javascript:wp_dp_listing_type_field_add('wp_dp_listing_type_pb_email', 'email', '<?php echo absint($rand_f_counter) ?>')" title="Email" data-type="email" data-name="custom_email"><i class="icon-mail"></i><?php echo wp_dp_plugin_text_srt('wp_dp_email'); ?>&nbsp;&nbsp;<span></span></a></li>
                            <li id="field-url-<?php echo absint($rand_f_counter) ?>"><a href="javascript:wp_dp_listing_type_field_add('wp_dp_listing_type_pb_url', 'url', '<?php echo absint($rand_f_counter) ?>')" title="URL" data-type="url" data-name="custom_url"><i class="icon-link2"></i><?php echo wp_dp_plugin_text_srt('wp_dp_url'); ?>&nbsp;&nbsp;<span></span></a></li>
                            <li id="field-range-<?php echo absint($rand_f_counter) ?>"><a href="javascript:wp_dp_listing_type_field_add('wp_dp_listing_type_pb_range', 'range', '<?php echo absint($rand_f_counter) ?>')" title="Range" data-type="range" data-name="custom_range"><i class=" icon-target2"></i><?php echo wp_dp_plugin_text_srt('wp_dp_range'); ?>&nbsp;&nbsp;<span></span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="cs-custom-fields">
                    <div id="cs-pb-formelements">
                        <?php
                        $wp_dp_count_node = 0;
                        $count_widget = 0;
                        $wp_dp_listing_type_cus_fields = get_post_meta($post->ID, "wp_dp_listing_type_cus_fields", true);
                        if (is_array($wp_dp_listing_type_cus_fields) && sizeof($wp_dp_listing_type_cus_fields) > 0) {

                            foreach ($wp_dp_listing_type_cus_fields as $f_key => $wp_dp_field) {
                                global $wp_dp_f_counter;
                                $wp_dp_f_counter = $f_key;
                                if (isset($wp_dp_field['type']) && $wp_dp_field['type'] == "text") {
                                    $wp_dp_count_node ++;
                                    $this->wp_dp_listing_type_pb_text(1);
                                } else if (isset($wp_dp_field['type']) && $wp_dp_field['type'] == "number") {
                                    $wp_dp_count_node ++;
                                    $this->wp_dp_listing_type_pb_number(1);
                                } else if (isset($wp_dp_field['type']) && $wp_dp_field['type'] == "section") {
                                    $wp_dp_count_node ++;
                                    $this->wp_dp_listing_type_pb_section(1);
                                } else if (isset($wp_dp_field['type']) && $wp_dp_field['type'] == "textarea") {
                                    $wp_dp_count_node ++;
                                    $this->wp_dp_listing_type_pb_textarea(1);
                                } else if (isset($wp_dp_field['type']) && $wp_dp_field['type'] == "dropdown") {
                                    $wp_dp_count_node ++;
                                    $this->wp_dp_listing_type_pb_dropdown(1);
                                } else if (isset($wp_dp_field['type']) && $wp_dp_field['type'] == "date") {
                                    $wp_dp_count_node ++;
                                    $this->wp_dp_listing_type_pb_date(1);
                                } else if (isset($wp_dp_field['type']) && $wp_dp_field['type'] == "email") {
                                    $wp_dp_count_node ++;
                                    $this->wp_dp_listing_type_pb_email(1);
                                } else if (isset($wp_dp_field['type']) && $wp_dp_field['type'] == "url") {
                                    $wp_dp_count_node ++;
                                    $this->wp_dp_listing_type_pb_url(1);
                                } else if (isset($wp_dp_field['type']) && $wp_dp_field['type'] == "range") {
                                    $wp_dp_count_node ++;
                                    $this->wp_dp_listing_type_pb_range(1);
                                }
                            }
                        }
                        ?>
                    </div>
                </div>

                <?php
                if (is_array($wp_dp_listing_type_cus_fields) && sizeof($wp_dp_listing_type_cus_fields) > 0) {
                    
                } else {
                    ?>
                    <div class="alert alert-warning" id="cs-pbwp-alert"><?php echo wp_dp_plugin_text_srt('wp_dp_please_insert_item') ?></div>
                    <?php
                }

                $wp_dp_opt_array = array(
                    'std' => 1,
                    'cust_name' => 'custom_fields_elements',
                    'cust_type' => 'hidden',
                    'return' => false,
                    'prefix_on' => false,
                );
                $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_array);
                ?>

                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        wp_dp_custom_fields_js();
                        chosen_selectionbox();
                    });
                    var counter = <?php echo esc_js($wp_dp_count_node); ?>;
                    function wp_dp_listing_type_field_add(action, f_type, f_counter) {
                        counter++;
                        var this_loader = $("#field-" + f_type + "-" + f_counter);
                        this_loader.find('span').html('<img src="<?php echo wp_dp::plugin_url() ?>assets/backend/images/ajax-loader.gif" alt="">');
                        var newCustomerForm = "action=" + action + '&counter=' + counter;
                        jQuery.ajax({
                            type: "POST",
                            url: "<?php echo esc_js(admin_url('admin-ajax.php')); ?>",
                            data: newCustomerForm,
                            success: function (data) {
                                jQuery("#cs-pb-formelements").append(data);
                                chosen_selectionbox();
                                this_loader.find('span').html('');
                            }
                        });
                    }
                </script> 
            </div>
            <?php
        }

        public function wp_dp_listing_type_pb_section($die = 0, $wp_dp_return = false) {

            global $wp_dp_f_counter, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text;

            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_section_string'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_section_small');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_section[label]',
                'title' => wp_dp_plugin_text_srt('wp_dp_section_text'),
                'std' => '',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_section[divider]',
                'title' => wp_dp_plugin_text_srt('wp_dp_divider_small'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_enable_disable_divider'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_section',
                'name' => 'wp_dp_cus_field_section[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'std' => '',
                'hint' => '',
            ));

            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'section', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);

            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);

            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        /*
         * Textarea field
         */

        public function wp_dp_listing_type_pb_textarea($die = 0, $wp_dp_return = false) {
            global $wp_dp_f_counter, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text;

            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_textarea_string'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_textarea');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_textarea[required]',
                'title' => wp_dp_plugin_text_srt('wp_dp_required'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_required_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[label]',
                'title' => wp_dp_plugin_text_srt('wp_dp_custom_field_title'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_custom_field_title_desc'),
                //'classes' => 'wp-dp-dev-req-field-admin',
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[meta_key]',
                'classes' => 'dir_meta_key_field wp-dp-dev-req-field-admin',
                'title' => wp_dp_plugin_text_srt('wp_dp_meta_key'),
                'check' => true,
                'std' => '',
                'desc' => wp_dp_plugin_text_srt('wp_dp_meta_key_desc'),
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[placeholder]',
                'title' => wp_dp_plugin_text_srt('wp_dp_place_holder'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_place_holder_desc'),
                'std' => '',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[rows]',
                'title' => wp_dp_plugin_text_srt('wp_dp_rows'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_rows_desc'),
                'std' => '5',
                'classes' => 'wp-dp-dev-req-field-admin wp-dp-number-field',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[cols]',
                'title' => wp_dp_plugin_text_srt('wp_dp_columns'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_columns_desc'),
                'std' => '25',
                'classes' => 'wp-dp-dev-req-field-admin wp-dp-number-field',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[default_value]',
                'title' => wp_dp_plugin_text_srt('wp_dp_default_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_default_value_desc'),
                'std' => '',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_textarea',
                'name' => 'wp_dp_cus_field_textarea[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_icon_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'textarea', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);
            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);
            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        /**
         * Start function how to create Text Fields
         */
        public function wp_dp_listing_type_pb_text($die = 0, $wp_dp_return = false) {
            global $wp_dp_f_counter, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text;


            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_text_string'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_custom_field_title');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_text[required]',
                'title' => wp_dp_plugin_text_srt('wp_dp_required'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_required_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_text[label]',
                //'classes' => 'wp-dp-dev-req-field-admin',
                'title' => wp_dp_plugin_text_srt('wp_dp_custom_field_title'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_custom_field_title_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_text[meta_key]',
                'classes' => 'dir_meta_key_field wp-dp-dev-req-field-admin',
                'title' => wp_dp_plugin_text_srt('wp_dp_meta_key'),
                'check' => true,
                'std' => '',
                'desc' => wp_dp_plugin_text_srt('wp_dp_meta_key_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_text[placeholder]',
                'title' => wp_dp_plugin_text_srt('wp_dp_place_holder'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_place_holder_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_text[enable_srch]',
                'title' => wp_dp_plugin_text_srt('wp_dp_enable_search'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'desc' => wp_dp_plugin_text_srt('wp_dp_enable_search_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_text[default_value]',
                'title' => wp_dp_plugin_text_srt('wp_dp_default_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_default_value_desc'),
                'std' => '',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_text',
                'name' => 'wp_dp_cus_field_text[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_icon_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'text', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);

            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);

            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        public function wp_dp_listing_type_pb_number($die = 0, $wp_dp_return = false) {
            global $wp_dp_f_counter, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text;


            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_number_string'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_number_small');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_number[required]',
                'title' => wp_dp_plugin_text_srt('wp_dp_required'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_required_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_number[label]',
                'title' => wp_dp_plugin_text_srt('wp_dp_custom_field_title'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_custom_field_title_desc'),
                //'classes' => 'wp-dp-dev-req-field-admin',
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_number[meta_key]',
                'classes' => 'dir_meta_key_field wp-dp-dev-req-field-admin',
                'title' => wp_dp_plugin_text_srt('wp_dp_meta_key'),
                'check' => true,
                'std' => '',
                'desc' => wp_dp_plugin_text_srt('wp_dp_meta_key_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_number[placeholder]',
                'title' => wp_dp_plugin_text_srt('wp_dp_place_holder'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_place_holder_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_number[enable_srch]',
                'title' => wp_dp_plugin_text_srt('wp_dp_enable_search'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'desc' => wp_dp_plugin_text_srt('wp_dp_enable_search_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_number[default_value]',
                'title' => wp_dp_plugin_text_srt('wp_dp_default_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_default_value_desc'),
                'std' => '',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_text',
                'name' => 'wp_dp_cus_field_number[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_icon_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'number', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);

            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);

            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        /**
         * Start function how to create Textarea Fields
         */
        public function wp_dp_enable_upload($die = 0, $wp_dp_return = false) {
            global $wp_dp_f_counter, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text;

            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_textarea_small'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_textarea_small');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_textarea[required]',
                'title' => wp_dp_plugin_text_srt('wp_dp_required'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_required_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_textarea[featured]',
                'title' => wp_dp_plugin_text_srt('wp_dp_listing_featured'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[label]',
                'title' => wp_dp_plugin_text_srt('wp_dp_title'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[meta_key]',
                'class' => 'dir_meta_key_field',
                'title' => wp_dp_plugin_text_srt('wp_dp_meta_key'),
                'check' => true,
                'std' => '',
                'desc' => wp_dp_plugin_text_srt('wp_dp_meta_key_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_textarea(array(
                'id' => '',
                'name' => 'cus_field_textarea[help]',
                'title' => wp_dp_plugin_text_srt('wp_dp_help_text'),
                'std' => '',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[placeholder]',
                'title' => wp_dp_plugin_text_srt('wp_dp_place_holder'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_place_holder_desc'),
                'std' => '',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[rows]',
                'title' => wp_dp_plugin_text_srt('wp_dp_rows'),
                'std' => '5',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[cols]',
                'title' => wp_dp_plugin_text_srt('wp_dp_columns'),
                'std' => '25',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_textarea[default_value]',
                'title' => wp_dp_plugin_text_srt('wp_dp_default_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_default_value_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_textarea[field_size]',
                'title' => wp_dp_plugin_text_srt('wp_dp_field_size'),
                'std' => '',
                'options' => array('small' => wp_dp_plugin_text_srt('wp_dp_small'), 'medium' => wp_dp_plugin_text_srt('wp_dp_medium'), 'large' => wp_dp_plugin_text_srt('wp_dp_large')),
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_textarea',
                'name' => 'wp_dp_cus_field_textarea[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_icon_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'textarea', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);
            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);
            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        /**
         * End function how to create Textarea Fields
         */

        /**
         * Start function how to create dropdown option fields
         */
        public function wp_dp_listing_type_pb_dropdown($die = 0, $wp_dp_return = false) {
            global $wp_dp_f_counter, $wp_dp_form_fields, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text, $wp_dp_Class;

            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_dropdown_string'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_dropdown');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_dropdown[required]',
                'title' => wp_dp_plugin_text_srt('wp_dp_required'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_required_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_dropdown[label]',
                'title' => wp_dp_plugin_text_srt('wp_dp_custom_field_title'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_custom_field_title_desc'),
                // 'classes' => 'wp-dp-dev-req-field-admin',
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_dropdown[meta_key]',
                'classes' => 'dir_meta_key_field wp-dp-dev-req-field-admin',
                'title' => wp_dp_plugin_text_srt('wp_dp_meta_key'),
                'check' => true,
                'std' => '',
                'desc' => wp_dp_plugin_text_srt('wp_dp_meta_key_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_dropdown[chosen_srch]',
                'title' => wp_dp_plugin_text_srt('wp_dp_chosen_search'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'desc' => wp_dp_plugin_text_srt('wp_dp_chosen_search_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_dropdown[enable_srch]',
                'title' => wp_dp_plugin_text_srt('wp_dp_enable_search'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'desc' => wp_dp_plugin_text_srt('wp_dp_enable_search_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_dropdown[multi]',
                'title' => wp_dp_plugin_text_srt('wp_dp_multi_select'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_multi_select_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_dropdown[post_multi]',
                'title' => wp_dp_plugin_text_srt('wp_dp_post_multi_select'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_post_multi_select_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_dropdown[first_value]',
                'title' => wp_dp_plugin_text_srt('wp_dp_first_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_first_value_desc'),
                'std' => '- select -',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_selectbox',
                'name' => 'wp_dp_cus_field_dropdown[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_icon_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= '
			<div class="form-elements field-dropdown-opt-values">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label>' . wp_dp_plugin_text_srt('wp_dp_options') . '</label>
                                        <p class="label-desc">' . wp_dp_plugin_text_srt('wp_dp_options_desc') . '</p>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';

            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter]['options']['value'])) {
                $wp_dp_opt_counter = 0;
                $wp_dp_radio_counter = 1;
                foreach ($wp_dp_listing_type_cus_fields[$wp_dp_f_counter]['options']['value'] as $wp_dp_option) {
                    $wp_dp_checked = (int) $wp_dp_listing_type_cus_fields[$wp_dp_f_counter]['options']['select'][0] == (int) $wp_dp_radio_counter ? ' checked="checked"' : '';
                    $wp_dp_opt_label = $wp_dp_listing_type_cus_fields[$wp_dp_f_counter]['options']['label'][$wp_dp_opt_counter];
                    $wp_dp_opt_img = $wp_dp_listing_type_cus_fields[$wp_dp_f_counter]['options']['img'][$wp_dp_opt_counter];
                    $wp_dp_fields_markup .= '
					<div class="pbwp-clone-field clearfix">';
                    $wp_dp_opt_array = array(
                        'cust_id' => 'cus_field_dropdown_selected_' . absint($wp_dp_counter),
                        'cust_name' => 'cus_field_dropdown[selected][' . absint($wp_dp_counter) . '][]',
                        'cust_type' => 'radio',
                        'extra_atr' => $wp_dp_checked,
                        'std' => $wp_dp_radio_counter,
                        'return' => true,
                    );
                    $wp_dp_fields_markup .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'cust_id' => 'cus_field_dropdown_options_' . absint($wp_dp_counter),
                        'cust_name' => 'cus_field_dropdown[options][' . absint($wp_dp_counter) . '][]',
                        'extra_atr' => ' data-type="option"',
                        'std' => $wp_dp_opt_label,
                        'classes' => 'input-small',
                        'return' => true,
                    );
                    $wp_dp_fields_markup .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'cust_id' => 'cus_field_dropdown_options_values_' . absint($wp_dp_counter),
                        'cust_name' => 'cus_field_dropdown[options_values][' . absint($wp_dp_counter) . '][]',
                        'std' => $wp_dp_option,
                        'classes' => 'input-small',
                        'return' => true,
                    );
                    $wp_dp_fields_markup .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);


                    $wp_dp_fields_markup .= '
                            <img src="' . esc_url(wp_dp::plugin_url() . 'assets/backend/images/add.png') . '" class="pbwp-clone-field" alt="' . wp_dp_plugin_text_srt('wp_dp_add_another') . '" style="cursor:pointer; margin:0 3px;">
                            <img src="' . esc_url(wp_dp::plugin_url() . 'assets/backend/images/remove.png') . '" alt="' . wp_dp_plugin_text_srt('wp_dp_remove_this') . '" class="pbwp-dpove-field" style="cursor:pointer;">
                    </div>';
                    $wp_dp_opt_counter ++;
                    $wp_dp_radio_counter ++;
                }
            } else {
                $wp_dp_fields_markup .= '
				<div class="pbwp-clone-field clearfix">';

                $wp_dp_opt_array = array(
                    'cust_id' => 'cus_field_dropdown_selected_' . absint($wp_dp_counter),
                    'cust_name' => 'cus_field_dropdown[selected][' . absint($wp_dp_counter) . '][]',
                    'cust_type' => 'radio',
                    'extra_atr' => ' checked="checked"',
                    'std' => '1',
                    'return' => true,
                );
                $wp_dp_fields_markup .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                $wp_dp_opt_array = array(
                    'cust_id' => 'cus_field_dropdown_options_' . absint($wp_dp_counter),
                    'cust_name' => 'cus_field_dropdown[options][' . absint($wp_dp_counter) . '][]',
                    'extra_atr' => ' data-type="option" placeholder="Label"',
                    'std' => '',
                    'classes' => 'input-small',
                    'return' => true,
                );
                $wp_dp_fields_markup .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                $wp_dp_opt_array = array(
                    'cust_id' => 'cus_field_dropdown_options_values_' . absint($wp_dp_counter),
                    'cust_name' => 'cus_field_dropdown[options_values][' . absint($wp_dp_counter) . '][]',
                    'extra_atr' => ' placeholder="Value"',
                    'std' => '',
                    'classes' => 'input-small',
                    'return' => true,
                );
                $wp_dp_fields_markup .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                $wp_dp_fields_markup .= '
                        <img src="' . esc_url($wp_dp_Class->plugin_url() . 'assets/backend/images/add.png') . '" class="pbwp-clone-field" alt="' . wp_dp_plugin_text_srt('wp_dp_add_another') . '" style="cursor:pointer; margin:0 3px;">
                        <img src="' . esc_url($wp_dp_Class->plugin_url() . 'assets/backend/images/remove.png') . '" alt="' . wp_dp_plugin_text_srt('wp_dp_remove_this') . '" class="pbwp-dpove-field" style="cursor:pointer;">
                </div>';
            }
            $wp_dp_fields_markup .= '
				</div>
			</div>';


            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'dropdown', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);
            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);
            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        /**
         * End function how to create dropdown option fields
         */

        /**
         * Start function how to create custom fields
         */
        public function wp_dp_listing_type_pb_date($die = 0, $wp_dp_return = false) {
            global $wp_dp_f_counter, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text;

            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_date_string'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_date_small');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_date[required]',
                'title' => wp_dp_plugin_text_srt('wp_dp_required'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_required_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_date[label]',
                'title' => wp_dp_plugin_text_srt('wp_dp_custom_field_title'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_custom_field_title_desc'),
                // 'classes' => 'wp-dp-dev-req-field-admin',
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_date[meta_key]',
                'classes' => 'dir_meta_key_field wp-dp-dev-req-field-admin ',
                'title' => wp_dp_plugin_text_srt('wp_dp_meta_key'),
                'check' => true,
                'std' => '',
                'desc' => wp_dp_plugin_text_srt('wp_dp_meta_key_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_date[enable_srch]',
                'title' => wp_dp_plugin_text_srt('wp_dp_enable_search'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'desc' => wp_dp_plugin_text_srt('wp_dp_enable_search_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_date[date_format]',
                'title' => wp_dp_plugin_text_srt('wp_dp_date_format'),
                'std' => 'd.m.Y',
                'desc' => wp_dp_plugin_text_srt('wp_dp_date_format_desc') . ' d.m.Y or Y/m/d',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_date',
                'name' => 'wp_dp_cus_field_date[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_icon_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'date', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);
            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);
            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        /**
         * End function how to create custom fields
         */

        /**
         * Start function how to create custom email fields
         */
        public function wp_dp_listing_type_pb_email($die = 0, $wp_dp_return = false) {
            global $wp_dp_f_counter, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text;

            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_email_string'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_user_meta_email');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_email[required]',
                'title' => wp_dp_plugin_text_srt('wp_dp_required'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_required_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_email[label]',
                // 'classes' => 'wp-dp-dev-req-field-admin',
                'title' => wp_dp_plugin_text_srt('wp_dp_custom_field_title'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_custom_field_title_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_email[meta_key]',
                'classes' => 'dir_meta_key_field wp-dp-dev-req-field-admin',
                'title' => wp_dp_plugin_text_srt('wp_dp_meta_key'),
                'check' => true,
                'std' => '',
                'desc' => wp_dp_plugin_text_srt('wp_dp_meta_key_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_email[placeholder]',
                'title' => wp_dp_plugin_text_srt('wp_dp_place_holder'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_place_holder_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_email[enable_srch]',
                'title' => wp_dp_plugin_text_srt('wp_dp_enable_search'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'desc' => wp_dp_plugin_text_srt('wp_dp_enable_search_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_email[default_value]',
                'title' => wp_dp_plugin_text_srt('wp_dp_default_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_default_value_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_email',
                'name' => 'wp_dp_cus_field_email[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_icon_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'email', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);
            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);
            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        /**
         * End function how to create custom email fields
         */

        /**
         * Start function how to create post custom url fields
         */
        public function wp_dp_listing_type_pb_url($die = 0, $wp_dp_return = false) {
            global $wp_dp_f_counter, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text;

            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_url_string'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_url');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_url[required]',
                'title' => wp_dp_plugin_text_srt('wp_dp_required'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_required_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_url[label]',
                'title' => wp_dp_plugin_text_srt('wp_dp_custom_field_title'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_custom_field_title_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_url[meta_key]',
                'classes' => 'dir_meta_key_field wp-dp-dev-req-field-admin',
                'title' => wp_dp_plugin_text_srt('wp_dp_meta_key'),
                'check' => true,
                'std' => '',
                'desc' => wp_dp_plugin_text_srt('wp_dp_meta_key_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_url[placeholder]',
                'title' => wp_dp_plugin_text_srt('wp_dp_place_holder'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_place_holder_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_url[enable_srch]',
                'title' => wp_dp_plugin_text_srt('wp_dp_enable_search'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'desc' => wp_dp_plugin_text_srt('wp_dp_enable_search_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_url[default_value]',
                'title' => wp_dp_plugin_text_srt('wp_dp_default_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_default_value_desc'),
                'std' => '',
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_url',
                'name' => 'wp_dp_cus_field_url[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_icon_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'url', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);
            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);
            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        /**
         * End function how to create post custom url fields
         */

        /**
         * Start function how to create post custom range in fields
         */
        public function wp_dp_listing_type_pb_range($die = 0, $wp_dp_return = false) {
            global $wp_dp_f_counter, $wp_dp_listing_type_cus_fields, $wp_dp_plugin_static_text;

            $wp_dp_fields_markup = '';
            if (isset($_REQUEST['counter'])) {
                $wp_dp_counter = $_REQUEST['counter'];
            } else {
                $wp_dp_counter = $wp_dp_f_counter;
            }
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_counter])) {
                $wp_dp_title = isset($wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) ? sprintf(wp_dp_plugin_text_srt('wp_dp_range_string'), $wp_dp_listing_type_cus_fields[$wp_dp_counter]['label']) : '';
            } else {
                $wp_dp_title = wp_dp_plugin_text_srt('wp_dp_range_small');
            }
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_range[required]',
                'title' => wp_dp_plugin_text_srt('wp_dp_required'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_required_desc'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_range[label]',
                'title' => wp_dp_plugin_text_srt('wp_dp_custom_field_title'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_custom_field_title_desc'),
                // 'classes' => 'wp-dp-dev-req-field-admin',
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_range[meta_key]',
                'classes' => 'dir_meta_key_field wp-dp-dev-req-field-admin',
                'title' => wp_dp_plugin_text_srt('wp_dp_meta_key'),
                'check' => true,
                'std' => '',
                'desc' => wp_dp_plugin_text_srt('wp_dp_meta_key_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_range[placeholder]',
                'title' => wp_dp_plugin_text_srt('wp_dp_place_holder'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_place_holder_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_range[min]',
                'title' => wp_dp_plugin_text_srt('wp_dp_minimum_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_minimum_value_desc'),
                'classes' => 'wp-dp-dev-req-field-admin wp-dp-number-field',
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_range[max]',
                'title' => wp_dp_plugin_text_srt('wp_dp_maximum_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_maximum_value_desc'),
                'classes' => 'wp-dp-dev-req-field-admin wp-dp-number-field',
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_range[increment]',
                'title' => wp_dp_plugin_text_srt('wp_dp_increment_step'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_increment_step_desc'),
                'classes' => 'wp-dp-dev-req-field-admin wp-dp-number-field',
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_range[enable_srch]',
                'title' => wp_dp_plugin_text_srt('wp_dp_enable_search'),
                'std' => '',
                'options' => array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes')),
                'desc' => wp_dp_plugin_text_srt('wp_dp_enable_search_desc'),
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_input_text(array(
                'id' => '',
                'name' => 'cus_field_range[default_value]',
                'title' => wp_dp_plugin_text_srt('wp_dp_default_value'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_default_value_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields_markup .= $this->wp_dp_fields_select(array(
                'id' => '',
                'name' => 'cus_field_range[srch_style]',
                'title' => wp_dp_plugin_text_srt('wp_dp_search_style'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_search_style_desc'),
                'std' => '',
                'options' => array('slider' => wp_dp_plugin_text_srt('wp_dp_slider'), 'dropdown' => wp_dp_plugin_text_srt('wp_dp_dropdown_small')),
                'hint' => '',
            ));

            $wp_dp_fields_markup .= $this->wp_dp_listing_type_fields_icon(array(
                'id' => 'fontawsome_icon_range',
                'name' => 'wp_dp_cus_field_range[fontawsome_icon]',
                'title' => wp_dp_plugin_text_srt('wp_dp_icon'),
                'desc' => wp_dp_plugin_text_srt('wp_dp_icon_desc'),
                'std' => '',
                'hint' => '',
            ));
            $wp_dp_fields = array('wp_dp_counter' => $wp_dp_counter, 'wp_dp_name' => 'range', 'wp_dp_title' => $wp_dp_title, 'wp_dp_markup' => $wp_dp_fields_markup);
            $wp_dp_output = $this->wp_dp_fields_layout($wp_dp_fields);
            if ($wp_dp_return == true) {
                return force_balance_tags($wp_dp_output, true);
            } else {
                echo force_balance_tags($wp_dp_output, true);
            }
            if ($die <> 1)
                die();
        }

        /**
         * end function how to create post custom range in fields
         */

        /**
         * Start function how to create post fields layout 
         */
        public function wp_dp_fields_layout($wp_dp_fields) {
            global $wp_dp_form_fields;
            $wp_dp_defaults = array('wp_dp_counter' => '1', 'wp_dp_name' => '', 'wp_dp_title' => '', 'wp_dp_markup' => '');
            extract(shortcode_atts($wp_dp_defaults, $wp_dp_fields));

            $wp_dp_html = '<div class="pb-item-container">';
            $wp_dp_section_class = '';
            if ($wp_dp_name == 'section') {
                $wp_dp_section_class = 'cs-cust-field-section';
            }
            $wp_dp_html .= '<div class="pbwp-legend ' . $wp_dp_section_class . '">';
            $wp_dp_html .= '<input type="hidden" name="wp_dp_cus_field_title[]" value="' . $wp_dp_name . '">';
            $wp_dp_html .= '<input type="hidden" name="wp_dp_cus_field_id[]" value="' . $wp_dp_counter . '">';

            if ($wp_dp_name == 'textarea') {
                $wp_dp_show_icon = 'icon-message';
            } else if ($wp_dp_name == 'dropdown') {
                $wp_dp_show_icon = 'icon-arrow-down';
            } else if ($wp_dp_name == 'date') {
                $wp_dp_show_icon = 'icon-perm_contact_calendar';
            } else if ($wp_dp_name == 'email') {
                $wp_dp_show_icon = 'icon-mail';
            } else if ($wp_dp_name == 'url') {
                $wp_dp_show_icon = 'icon-link2';
            } else if ($wp_dp_name == 'range') {
                $wp_dp_show_icon = 'icon-target2';
            } else if ($wp_dp_name == 'section') {
                $wp_dp_show_icon = 'icon-section';
            } else if ($wp_dp_name == 'number') {
                $wp_dp_show_icon = 'icon-file-text';
            } else {
                $wp_dp_show_icon = 'icon-new-message';
            }

            $wp_dp_html .= '
                <div class="pbwp-label"><i class="' . $wp_dp_show_icon . '"></i> ' . esc_attr($wp_dp_title) . ' </div>
                <div class="pbwp-actions">
                    <a class="pbwp-dpove" href="javascript:void(0);"><i class="icon-cancel-circle"></i></a>
                    <a class="pbwp-toggle" href="javascript:void(0);"><i class="icon-caret-down"></i></a>
                </div>
            </div>
            <div class="pbwp-form-holder" style="display:none;">';
            $wp_dp_html .= $wp_dp_markup;
            $wp_dp_html .= '	
                    </div>
            </div>';

            return force_balance_tags($wp_dp_html, true);
        }

        /**
         * End function how to create post fields layout in html
         */

        /**
         * Start function how to create post custom fields in input
         */
        public function wp_dp_fields_input_text($params = '') {
            global $wp_dp_f_counter, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_listing_type_cus_fields;
            $wp_dp_output = '';
            $wp_dp_output .= '<script>jQuery(document).ready(function($) {
                                    //wp_dp_check_fields_avail();
                            });</script>';
            extract($params);

            $set_meta_key_class = '';
            if (isset($class) && $class == 'dir_meta_key_field') {
                $set_meta_key_class = 'dir-res-meta-key-field';
            }

            $wp_dp_label = substr($name, strpos($name, '['), strpos($name, ']'));
            $wp_dp_label = str_replace(array('[', ']'), array('', ''), $wp_dp_label);
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter])) {
                $wp_dp_value = isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label]) ? $wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label] : '';
            }
            if (isset($wp_dp_value) && $wp_dp_value != '') {
                $value = $wp_dp_value;
            } else {
                $value = $std;
            }
            $wp_dp_rand_id = time();
            $html_id = $id != '' ? 'wp_dp_' . sanitize_html_class($id) . '' : '';
            $html_name = 'wp_dp_' . $name . '[]';
            $wp_dp_check_con = '';
            if (isset($check) && $check == true) {
                $html_id = 'check_field_name_' . $wp_dp_rand_id;
            }
            $classes_html = '';
            if (isset($classes)) {
                $classes_html = $classes;
            }
            $extra_attributes = '';
            if (isset($extra_atr)) {
                $extra_attributes = $extra_atr;
            }
            $desc = isset($desc) ? $desc : '';
            $wp_dp_output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                'name' => $title,
                'hint_text' => $hint,
                'label_desc' => $desc,
            ));
            $wp_dp_opt_array = array(
                'id' => $id,
                'cust_id' => $html_id,
                'classes' => $set_meta_key_class . ' ' . $classes_html,
                'extra_atr' => $extra_attributes,
                'cust_name' => $html_name,
                'std' => $value,
                'return' => true,
            );

            $wp_dp_output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);


            $wp_dp_output .= '<span class="name-checking"></span>';

            $wp_dp_output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                'desc' => '',
            ));
            return force_balance_tags($wp_dp_output);
        }

        /**
         * end function how to create post custom fields in input
         */

        /**
         * Start function how to create post custom fields in input textarea
         */
        public function wp_dp_fields_input_textarea($params = '') {
            global $wp_dp_f_counter, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_listing_type_cus_fields;
            $wp_dp_output = '';
            extract($params);
            $wp_dp_label = substr($name, strpos($name, '['), strpos($name, ']'));
            $wp_dp_label = str_replace(array('[', ']'), array('', ''), $wp_dp_label);
            $wp_dp_output .= '<script>jQuery(document).ready(function($) {
                                    //wp_dp_check_fields_avail();
                            });</script>';
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter])) {
                $wp_dp_value = isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label]) ? $wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label] : '';
            }
            if (isset($wp_dp_value) && $wp_dp_value != '') {
                $value = $wp_dp_value;
            } else {
                $value = $std;
            }
            $html_id = $id != '' ? 'wp_dp_' . sanitize_html_class($id) : '';
            $html_name = 'wp_dp_' . $name . '[]';
            $desc = isset($desc) ? $desc : '';
            $wp_dp_output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                'name' => $title,
                'hint_text' => $hint,
                'label_desc' => $desc,
            ));

            $wp_dp_opt_array = array(
                'id' => $id,
                'cust_id' => $html_id,
                'cust_name' => $html_name,
                'std' => $value,
                'return' => true,
            );

            $wp_dp_output .= $wp_dp_form_fields->wp_dp_form_textarea_render($wp_dp_opt_array);

            $wp_dp_output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                'desc' => '',
            ));
            return force_balance_tags($wp_dp_output);
        }

        /**
         * end function how to create post custom fields in input textarea
         */

        /**
         * Start function how to create post custom select fields
         */
        public function wp_dp_fields_select($params = '') {
            global $wp_dp_f_counter, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_listing_type_cus_fields;
            $wp_dp_output = '';
            extract($params);
            $wp_dp_output .= '<script>jQuery(document).ready(function($) {
                           	  //wp_dp_check_fields_avail();
                           });
			</script>';

            $wp_dp_label = substr($name, strpos($name, '['), strpos($name, ']'));
            $wp_dp_label = str_replace(array('[', ']'), array('', ''), $wp_dp_label);
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter])) {
                $wp_dp_value = isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label]) ? $wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label] : '';
            }
            if (isset($wp_dp_value) && $wp_dp_value != '') {
                $value = $wp_dp_value;
            } else {
                $value = $std;
            }
            $html_id = $id != '' ? 'wp_dp_' . sanitize_html_class($id) . '' : '';
            $html_name = 'wp_dp_' . $name . '[]';
            $html_class = 'chosen-select-no-single';
            $desc = isset($desc) ? $desc : '';
            $wp_dp_output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                'name' => $title,
                'label_desc' => $desc,
                'hint_text' => $hint,
            ));

            $wp_dp_opt_array = array(
                'id' => $id,
                'cust_id' => $html_id,
                'cust_name' => $html_name,
                'std' => $value,
                'classes' => $html_class,
                'options' => $options,
                'return' => true,
            );

            $wp_dp_output .= $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);

            $wp_dp_output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                'desc' => '',
            ));

            return force_balance_tags($wp_dp_output);
        }

        /**
         * end function how to create post custom select fields
         */

        /**
         * Start function how to create post custom icon fields
         */
        public function wp_dp_listing_type_fields_icon($params = '') {
            global $wp_dp_f_counter, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_listing_type_cus_fields;
            $wp_dp_output = '';
            extract($params);
            $wp_dp_output .= '';
            $rand_id = rand(0, 999999);
            $wp_dp_value_group = 'default';
            $wp_dp_label = substr($name, strpos($name, '['), strpos($name, ']'));
            $wp_dp_label = str_replace(array('[', ']'), array('', ''), $wp_dp_label);
            if (isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter])) {
                $wp_dp_value = isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label]) ? $wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label] : '';
                $wp_dp_value_group = isset($wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label . '_group']) ? $wp_dp_listing_type_cus_fields[$wp_dp_f_counter][$wp_dp_label . '_group'] : 'default';
                // echo '<pre>';
                //print_r( $wp_dp_listing_type_cus_fields );
                //echo '</pre>';
            }
            if (isset($wp_dp_value) && $wp_dp_value != '') {
                $value = $wp_dp_value;
            } else {
                $value = $std;
            }
            $html_id = $id != '' ? 'wp_dp_' . sanitize_html_class($id) . '' : '';
            $html_name = 'wp_dp_' . $name . '[]';
            $html_group_name = str_replace('][]', '_group][]', $html_name);
            $html_group_name = str_replace('wp_dp_wp_dp_', 'wp_dp_', $html_group_name);
            $html_class = 'chosen-select-no-single';
            $desc = isset($desc) ? $desc : '';
            $wp_dp_output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                'name' => $title,
                'hint_text' => $hint,
                'label_desc' => $desc,
            ));

            //$wp_dp_output .= wp_dp_iconlist_plugin_options( $value, $id . $wp_dp_f_counter . $rand_id, $name );
            $wp_dp_output .= apply_filters('cs_icons_fields', $value, $id . $wp_dp_f_counter . $rand_id, $name, $wp_dp_value_group, $html_group_name);

            $wp_dp_output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                'desc' => '',
            ));

            return force_balance_tags($wp_dp_output);
        }

        /**
         * end function how to create post custom icon fields
         */

        /**
         * Start function how to save array of fields
         */
        public function wp_dp_save_array($wp_dp_sec_counter = 0, $wp_dp_type = '', $cus_field_array = array()) {
            $wp_dp_fields = array('divider', 'required', 'featured', 'label', 'meta_key', 'placeholder', 'chosen_srch', 'enable_srch', 'default_value', 'fontawsome_icon', 'fontawsome_icon_group', 'help', 'rows', 'cols', 'multi', 'post_multi', 'first_value', 'collapse_search', 'field_size', 'date_format', 'min', 'max', 'increment', 'enable_inputs', 'srch_style', 'enable_divider');
            $cus_field_array['type'] = $wp_dp_type;
            foreach ($wp_dp_fields as $field) {
                if (isset($_POST["wp_dp_cus_field_{$wp_dp_type}"][$field][$wp_dp_sec_counter])) {
                    $cus_field_array[$field] = $_POST["wp_dp_cus_field_{$wp_dp_type}"][$field][$wp_dp_sec_counter];
                }
            }
            return $cus_field_array;
        }

        /**
         * end function how to save array of fields
         */

        /**
         * Start function how to update fields
         */
        public function wp_dp_update_custom_fields($post_id) {
            $wp_dp_obj = new wp_dp_listing_type_dynamic_fields();
            $text_counter = $number_counter = $section_counter = $textarea_counter = $dropdown_counter = $date_counter = $email_counter = $url_counter = $range_counter = $cus_field_counter = $error = 0;
            $cus_field = array();

            if (isset($_POST['wp_dp_cus_field_id']) && sizeof($_POST['wp_dp_cus_field_id']) > 0) {
                foreach ($_POST['wp_dp_cus_field_id'] as $keys => $values) {
                    if ($values != '') {
                        $wp_dp_rand_numb = rand(1342121, 9974532);
                        $cus_field_array = array();
                        $wp_dp_type = isset($_POST["wp_dp_cus_field_title"][$cus_field_counter]) ? $_POST["wp_dp_cus_field_title"][$cus_field_counter] : '';
                        switch ($wp_dp_type) {
                            case('text'):
                                $cus_field_array = $wp_dp_obj->wp_dp_save_array($text_counter, $wp_dp_type, $cus_field_array);
                                $text_counter ++;
                                break;
                            case('number'):
                                $cus_field_array = $wp_dp_obj->wp_dp_save_array($number_counter, $wp_dp_type, $cus_field_array);
                                $number_counter ++;
                                break;
                            case('section'):
                                $cus_field_array = $wp_dp_obj->wp_dp_save_array($section_counter, $wp_dp_type, $cus_field_array);
                                $section_counter ++;
                                break;
                            case('textarea'):
                                $cus_field_array = $wp_dp_obj->wp_dp_save_array($textarea_counter, $wp_dp_type, $cus_field_array);
                                $textarea_counter ++;
                                break;
                            case('dropdown'):
                                $cus_field_array = $wp_dp_obj->wp_dp_save_array($dropdown_counter, $wp_dp_type, $cus_field_array);
                                if (isset($_POST["cus_field_{$wp_dp_type}"]['options_values'][$values]) && (strlen(implode($_POST["cus_field_{$wp_dp_type}"]['options_values'][$values])) != 0)) {
                                    $cus_field_array['options'] = array();
                                    $option_counter = 0;

                                    foreach ($_POST["cus_field_{$wp_dp_type}"]['options_values'][$values] as $option) {
                                        if ($option != '') {
                                            $option = ltrim(rtrim($option));
                                            if ($_POST["cus_field_{$wp_dp_type}"]['options'][$values][$option_counter] != '') {
                                                $cus_field_array['options']['select'][] = isset($_POST["cus_field_{$wp_dp_type}"]['selected'][$values][$option_counter]) ? $_POST["cus_field_{$wp_dp_type}"]['selected'][$values][$option_counter] : '';
                                                $cus_field_array['options']['label'][] = isset($_POST["cus_field_{$wp_dp_type}"]['options'][$values][$option_counter]) ? $_POST["cus_field_{$wp_dp_type}"]['options'][$values][$option_counter] : '';
                                                $cus_field_array['options']['value'][] = isset($option) && $option != '' ? strtolower(str_replace(" ", "-", $option)) : '';
                                                $cus_field_array['options']['img'][] = isset($_POST["cus_field_{$wp_dp_type}"]['options_imgs'][$values][$option_counter]) ? $_POST["cus_field_{$wp_dp_type}"]['options_imgs'][$values][$option_counter] : '';
                                            }
                                        }
                                        $option_counter ++;
                                    }
                                } else {
                                    $error = 1;
                                }
                                $dropdown_counter ++;
                                break;
                            case('date'):
                                $cus_field_array = $wp_dp_obj->wp_dp_save_array($date_counter, $wp_dp_type, $cus_field_array);
                                $date_counter ++;
                                break;
                            case('email'):
                                $cus_field_array = $wp_dp_obj->wp_dp_save_array($email_counter, $wp_dp_type, $cus_field_array);
                                $email_counter ++;
                                break;
                            case('url'):
                                $cus_field_array = $wp_dp_obj->wp_dp_save_array($url_counter, $wp_dp_type, $cus_field_array);
                                $url_counter ++;
                                break;
                            case('range'):
                                $cus_field_array = $wp_dp_obj->wp_dp_save_array($range_counter, $wp_dp_type, $cus_field_array);
                                $range_counter ++;
                                break;
                        }
                        $cus_field[$wp_dp_rand_numb] = $cus_field_array;
                        $cus_field_counter ++;
                    }
                }
            }

            if ($error == 0) {
                update_post_meta($post_id, "wp_dp_listing_type_cus_fields", $cus_field);
            }
        }

        public function wp_dp_check_fields_avail() {
            global $wp_dp_plugin_static_text;


            $wp_dp_json = array();
            $wp_dp_temp_names = array();
            $wp_dp_temp_names_1 = array();
            $wp_dp_temp_names_2 = array();
            $wp_dp_temp_names_3 = array();
            $wp_dp_temp_names_4 = array();
            $wp_dp_temp_names_5 = array();
            $wp_dp_temp_names_6 = array();
            $wp_dp_field_name = $_REQUEST['name'];
            $post_id = isset($_POST['wp_dp_cus_field_text']['meta_key']) ? $_POST['wp_dp_cus_field_text']['meta_key'] : '';
            $post_id = isset($_POST['wp_dp_cus_field_number']['meta_key']) ? $_POST['wp_dp_cus_field_number']['meta_key'] : '';
            $wp_dp_listing_type_cus_fields = get_post_meta($post_id, "wp_dp_listing_type_cus_fields", true);
            $form_field_names = isset($_REQUEST['wp_dp_cus_field_text']['meta_key']) ? $_REQUEST['wp_dp_cus_field_text']['meta_key'] : array();
            $form_field_names_0 = isset($_REQUEST['wp_dp_cus_field_number']['meta_key']) ? $_REQUEST['wp_dp_cus_field_number']['meta_key'] : array();
            $form_field_names_1 = isset($_REQUEST['wp_dp_cus_field_textarea']['meta_key']) ? $_REQUEST['wp_dp_cus_field_textarea']['meta_key'] : array();
            $form_field_names_2 = isset($_REQUEST['wp_dp_cus_field_dropdown']['meta_key']) ? $_REQUEST['wp_dp_cus_field_dropdown']['meta_key'] : array();
            $form_field_names_3 = isset($_REQUEST['wp_dp_cus_field_date']['meta_key']) ? $_REQUEST['wp_dp_cus_field_date']['meta_key'] : array();
            $form_field_names_4 = isset($_REQUEST['wp_dp_cus_field_email']['meta_key']) ? $_REQUEST['wp_dp_cus_field_email']['meta_key'] : array();
            $form_field_names_5 = isset($_REQUEST['wp_dp_cus_field_url']['meta_key']) ? $_REQUEST['wp_dp_cus_field_url']['meta_key'] : array();
            $form_field_names_6 = isset($_REQUEST['wp_dp_cus_field_range']['meta_key']) ? $_REQUEST['wp_dp_cus_field_range']['meta_key'] : array();
            $form_field_names = array_merge($form_field_names, $form_field_names_0, $form_field_names_1, $form_field_names_2, $form_field_names_3, $form_field_names_4, $form_field_names_5, $form_field_names_6);
            $length = count(array_keys($form_field_names, $wp_dp_field_name));
            if ($wp_dp_field_name == '') {
                $wp_dp_json['type'] = 'error';
                $wp_dp_json['message'] = '<i class="icon-times"></i> ' . wp_dp_plugin_text_srt('wp_dp_field_name_required');
            } else {
                if (is_array($wp_dp_listing_type_cus_fields) && sizeof($wp_dp_listing_type_cus_fields) > 0) {
                    $success = 1;
                    foreach ($wp_dp_listing_type_cus_fields as $field_key => $wp_dp_field) {
                        if (isset($wp_dp_field['type'])) {
                            if (preg_match('/\s/', $wp_dp_field_name)) {
                                $wp_dp_json['type'] = 'error';
                                $wp_dp_json['message'] = '<i class="icon-times"></i> ' . wp_dp_plugin_text_srt('wp_dp_whitespaces_not_allowed');
                                echo json_encode($wp_dp_json);
                                die();
                            }
                            if (preg_match('/[\'^�$%&*()}{@#~?><>,|=+�]/', $wp_dp_field_name)) {
                                $wp_dp_json['type'] = 'error';
                                $wp_dp_json['message'] = '<i class="icon-times"></i> ' . wp_dp_plugin_text_srt('wp_dp_special_characters_not_allowed');
                                echo json_encode($wp_dp_json);
                                die();
                            }
                            if (trim($wp_dp_field['type']) == trim($wp_dp_field_name)) {

                                if (in_array(trim($wp_dp_field_name), $form_field_names) && $length > 1) {
                                    $wp_dp_json['type'] = 'error';
                                    $wp_dp_json['message'] = '<i class="icon-times"></i> ' . wp_dp_plugin_text_srt('wp_dp_name_already_exists');
                                    echo json_encode($wp_dp_json);
                                    die();
                                }
                            } else {
                                if (in_array(trim($wp_dp_field_name), $form_field_names) && $length > 1) {
                                    $wp_dp_json['type'] = 'error';
                                    $wp_dp_json['message'] = '<i class="icon-times"></i> ' . wp_dp_plugin_text_srt('wp_dp_name_already_exists');
                                    echo json_encode($wp_dp_json);
                                    die();
                                }
                            }
                        }
                    }
                    $wp_dp_json['type'] = 'success';
                    $wp_dp_json['message'] = '<i class="icon-checkmark6"></i> ' . wp_dp_plugin_text_srt('wp_dp_listing_custom_name_available');
                } else {
                    if (preg_match('/\s/', $wp_dp_field_name)) {
                        $wp_dp_json['type'] = 'error';
                        $wp_dp_json['message'] = '<i class="icon-times"></i> ' . wp_dp_plugin_text_srt('wp_dp_whitespaces_not_allowed');
                        echo json_encode($wp_dp_json);
                        die();
                    }
                    if (preg_match('/[\'^�$%&*()}{@#~?><>,|=+]/', $wp_dp_field_name)) {
                        $wp_dp_json['type'] = 'error';
                        $wp_dp_json['message'] = '<i class="icon-times"></i> ' . wp_dp_plugin_text_srt('wp_dp_special_characters_not_allowed');
                        echo json_encode($wp_dp_json);
                        die();
                    }
                    if (in_array(trim($wp_dp_field_name), $form_field_names) && $length > 1) {
                        $wp_dp_json['type'] = 'error';
                        $wp_dp_json['message'] = '<i class="icon-times"></i> ' . wp_dp_plugin_text_srt('wp_dp_name_already_exists');
                    } else {
                        $wp_dp_json['type'] = 'success';
                        $wp_dp_json['message'] = '<i class="icon-checkmark6"></i> ' . wp_dp_plugin_text_srt('wp_dp_listing_custom_name_available');
                    }
                }
            }
            echo json_encode($wp_dp_json);
            die();
        }

    }

    global $wp_dp_listing_type_fields;

    $wp_dp_listing_type_fields = new Wp_dp_Listing_Type_Dynamic_Fields();
}