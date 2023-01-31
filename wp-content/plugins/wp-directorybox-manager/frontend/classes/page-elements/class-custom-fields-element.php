<?php
/**
 * File Type: Nearby Listings Page Element
 */
if ( ! class_exists('wp_dp_custom_fields_element') ) {

    class wp_dp_custom_fields_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_custom_fields_html', array( $this, 'wp_dp_custom_fields_html_callback' ), 11, 3);
            add_filter('wp_dp_custom_fields', array( $this, 'wp_dp_custom_fields_callback' ), 11, 7);
            add_filter('wp_dp_featured_custom_fields', array( $this, 'wp_dp_featured_custom_fields_callback' ), 11, 4);
        }

        public function wp_dp_custom_fields_callback($listing_id = '', $custom_fields = array(), $fields_number = '', $field_label = true, $field_icon = true, $custom_value_position = true, $view = '') {
            global $post, $wp_dp_post_listing_types;
            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }
            $content = '';
            if ( $listing_id != '' && (($fields_number != '' && $fields_number > 0) || $fields_number == '') ) {
                $listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                $wp_dp_listing_type_cus_fields = $wp_dp_post_listing_types->wp_dp_types_custom_fields_array($listing_type);
                if ( is_array($wp_dp_listing_type_cus_fields) && isset($wp_dp_listing_type_cus_fields) && ! empty($wp_dp_listing_type_cus_fields) ) {
                    ob_start();
                    $custom_field_flag = 1;
                    foreach ( $wp_dp_listing_type_cus_fields as $cus_fieldvar => $cus_field ) {
                        if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] <> '' ) {
                            $cus_field_value_arr = get_post_meta($listing_id, $cus_field['meta_key'], true);
                            $cus_field_label_arr = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_field_icon_arr = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_field_icon_group_arr = isset($cus_field['fontawsome_icon_group']) ? $cus_field['fontawsome_icon_group'] : 'default';
                            $cus_format = isset($cus_field['date_format']) ? $cus_field['date_format'] : '';
                            $type = isset($cus_field['type']) ? $cus_field['type'] : '';

                            if ( $type == 'dropdown' ) {
                                $drop_down_arr = array();
                                $cut_field_flag = 0;
                                foreach ( $cus_field['options']['value'] as $key => $cus_field_options_value ) {

                                    $drop_down_arr[$cus_field_options_value] = force_balance_tags($cus_field['options']['label'][$cut_field_flag]);
                                    $cut_field_flag ++;
                                }
                            }

                            if ( is_array($cus_field_value_arr) ) {
                                $cus_field_value_arr = array_filter($cus_field_value_arr);
                            }
                            if ( isset($cus_field_value_arr) && (is_array($cus_field_value_arr) && ! empty($cus_field_value_arr)) || ( ! is_array($cus_field_value_arr) && $cus_field_value_arr <> '') ) {
                                ?>
                                <li>
                                    <?php
                                    if ( isset($cus_field_icon_arr) && $cus_field_icon_arr <> '' && $field_icon == true ) {
                                        wp_enqueue_style('cs_icons_data_css_' . $cus_field_icon_group_arr);
                                        ?>
                                        <i class="<?php echo esc_html($cus_field_icon_arr) ?>"></i>
                                        <?php
                                    }
                                    if ( is_array($cus_field_value_arr) ) {
                                        if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type == 'date' ) {
                                            if ( $view == 'detail-view' ) {
                                                echo '<span class="field-label">' . esc_html($cus_field_label_arr) . '</span>: ';
                                            } else {
                                                echo '<span>' . esc_html($cus_field_label_arr) . '</span>';
                                            }
                                        }
                                        foreach ( $cus_field_value_arr as $key => $single_value ) {
                                            if ( $single_value != '' ) {
                                                if ( isset($cus_format) && $cus_format != '' ) {
                                                    if ( $view == 'detail-view' ) {
                                                        echo '<span class="field-value">' . date($cus_format, $single_value) . '</span>';
                                                    } else {
                                                        echo date($cus_format, $single_value);
                                                    }
                                                } else if ( $type == 'dropdown' && isset($drop_down_arr[$single_value]) && $drop_down_arr[$single_value] != '' ) {
                                                    if ( $view == 'detail-view' ) {
                                                        echo '<span class="field-value">' . esc_html($drop_down_arr[$single_value]) . '</span>';
                                                    } else {
                                                        echo '<span>' . esc_html($drop_down_arr[$single_value]) . '</span>';
                                                    }
                                                } else {
                                                    if ( $view == 'detail-view' ) {
                                                        echo '<span class="field-value">' . esc_html(ucwords(str_replace("-", " ", $single_value))) . '</span>';
                                                    } else {
                                                        echo '<span>' . esc_html(ucwords(str_replace("-", " ", $single_value))) . '</span>';
                                                    }
                                                }
                                            }
                                        }
                                        if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type != 'dropdown' && $type != 'date' ) {
                                            if ( $view == 'detail-view' ) {
                                                echo '<span class="field-label">' . esc_html($cus_field_label_arr) . '</span>';
                                            } else {
                                                echo '<span>' . esc_html($cus_field_label_arr) . '</span>';
                                            }
                                        }
                                    } else {

                                        if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type == 'date' ) {
                                            if ( $custom_value_position ) {
                                                if ( $field_label == true ) {
                                                    if ( $view == 'detail-view' ) {
                                                        echo '<span class="field-label">' . esc_html($cus_field_label_arr) . '</span>: ';
                                                    } else {
                                                        echo '&nbsp;' . esc_html($cus_field_label_arr);
                                                    }
                                                }
                                            }
                                        }

                                        if ( isset($cus_format) && $cus_format != '' ) {
                                            if ( $view == 'detail-view' ) {
                                                echo '<span class="field-value">' . date($cus_format, $cus_field_value_arr) . '</span>';
                                            } else {
                                                echo date($cus_format, $cus_field_value_arr);
                                            }
                                        } else if ( $type == 'dropdown' && isset($drop_down_arr[$cus_field_value_arr]) && $drop_down_arr[$cus_field_value_arr] != '' ) {
                                            if ( $view == 'detail-view' ) {
                                                echo '<span class="field-value">' . esc_html($drop_down_arr[$cus_field_value_arr]) . '</span>';
                                            } else {
                                                echo esc_html($drop_down_arr[$cus_field_value_arr]);
                                            }
                                        } else {
                                            if ( $custom_value_position ) {
                                                if ( $view == 'detail-view' ) {
                                                    echo '<span class="field-value">' . esc_html(ucwords(str_replace("-", " ", $cus_field_value_arr))) . '</span>';
                                                } else {
						     echo '&nbsp;' . esc_html($cus_field_label_arr);
                                                    //echo esc_html(ucwords(str_replace("-", " ", $cus_field_value_arr)));
                                                }
                                            }
                                        }
                                        if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type != 'dropdown' && $type != 'date' ) {
                                            if ( $custom_value_position ) {
                                                if ( $field_label == true ) {
                                                    if ( $view == 'detail-view' ) {
                                                        echo '<span class="field-label">' . esc_html($cus_field_label_arr) . '</span>';
                                                    } else {
                                                        //echo '&nbsp;' . esc_html($cus_field_label_arr);
							echo esc_html(ucwords(str_replace("-", " ", $cus_field_value_arr)));
                                                    }
                                                }
                                            }
                                        }

                                        if ( $custom_value_position == false ) { // done only for view medium list listing
                                            echo '<span>' . esc_html($cus_field_label_arr) . '</span>';
                                            echo '<small>' . esc_html(ucwords(str_replace("-", " ", $cus_field_value_arr))) . '</small>';
                                        }
                                    }
                                    ?>
                                </li>
                                <?php
                                $custom_field_flag ++;
                                if ( $custom_field_flag > $fields_number && $fields_number != '' ) {
                                    break;
                                }
                            }
                        }
                    }
                    $content = ob_get_clean();
                }
            }
            $custom_fields['content'] = $content;
            return $custom_fields;
        }

        public function wp_dp_featured_custom_fields_callback($listing_id = '', $custom_fields = array(), $fields_number = '', $field_label = true) {
            global $post, $wp_dp_post_listing_types;
            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }
            $content = '';
            if ( $listing_id != '' && (($fields_number != '' && $fields_number > 0) || $fields_number == '') ) {
                $listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                $wp_dp_listing_type_cus_fields = $wp_dp_post_listing_types->wp_dp_types_custom_fields_array($listing_type);
                if ( is_array($wp_dp_listing_type_cus_fields) && isset($wp_dp_listing_type_cus_fields) && ! empty($wp_dp_listing_type_cus_fields) ) {
                    ob_start();
                    $custom_field_flag = 1;
                    foreach ( $wp_dp_listing_type_cus_fields as $cus_fieldvar => $cus_field ) {
                        if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] <> '' ) {
                            $cus_field_value_arr = get_post_meta($listing_id, $cus_field['meta_key'], true);
                            $cus_field_label_arr = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_field_icon_arr = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_field_icon_group_arr = isset($cus_field['fontawsome_icon_group']) ? $cus_field['fontawsome_icon_group'] : 'default';
                            $cus_format = isset($cus_field['date_format']) ? $cus_field['date_format'] : '';
                            $type = isset($cus_field['type']) ? $cus_field['type'] : '';

                            if ( $type == 'dropdown' ) {
                                $drop_down_arr = array();
                                $cut_field_flag = 0;
                                foreach ( $cus_field['options']['value'] as $key => $cus_field_options_value ) {
                                    $drop_down_arr[$cus_field_options_value] = force_balance_tags($cus_field['options']['label'][$cut_field_flag]);
                                    $cut_field_flag ++;
                                }
                            }
                            if ( is_array($cus_field_value_arr) ) {
                                $cus_field_value_arr = array_filter($cus_field_value_arr);
                            }
                            if ( isset($cus_field_value_arr) && (is_array($cus_field_value_arr) && ! empty($cus_field_value_arr)) || ( ! is_array($cus_field_value_arr) && $cus_field_value_arr <> '') ) {
                                ?>
                                <li class="has-border">
                                    <?php
                                    if ( isset($cus_field_icon_arr) && $cus_field_icon_arr <> '' ) {
                                        wp_enqueue_style('cs_icons_data_css_' . $cus_field_icon_group_arr);
                                        ?>
                                        <i class="<?php echo esc_html($cus_field_icon_arr) ?>"></i>
                                        <?php
                                    }
                                    if ( is_array($cus_field_value_arr) ) {
                                        if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type != 'dropdown' ) {
                                            echo '<span>' . esc_html($cus_field_label_arr) . '</span>';
                                        }
                                        foreach ( $cus_field_value_arr as $key => $single_value ) {
                                            if ( $single_value != '' ) {
                                                if ( isset($cus_format) && $cus_format != '' ) {
                                                    echo date($cus_format, $single_value);
                                                } else if ( $type == 'dropdown' && isset($drop_down_arr[$single_value]) && $drop_down_arr[$single_value] != '' ) {
                                                    echo '<span>' . esc_html($drop_down_arr[$single_value]) . '</span>';
                                                } else {
                                                    echo '<span>' . esc_html(ucwords(str_replace("-", " ", $single_value))) . '</span>';
                                                }
                                            }
                                        }
                                    } else {
                                        if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type != 'dropdown' ) {
                                            if ( $field_label == true ) {
                                                echo esc_html($cus_field_label_arr);
                                            }
                                        }
                                        if ( isset($cus_format) && $cus_format != '' ) {
                                            echo '<span>' . date($cus_format, $cus_field_value_arr) . '</span>';
                                        } else if ( $type == 'dropdown' && isset($drop_down_arr[$cus_field_value_arr]) && $drop_down_arr[$cus_field_value_arr] != '' ) {
                                            echo '<span>' . esc_html($drop_down_arr[$cus_field_value_arr]) . '</span>';
                                        } else {
                                            echo '<span>' . esc_html(ucwords(str_replace("-", " ", $cus_field_value_arr))) . '</span>';
                                            ;
                                        }
                                    }
                                    ?>
                                </li>
                                <?php
                                $custom_field_flag ++;
                                if ( $custom_field_flag > $fields_number && $fields_number != '' ) {
                                    break;
                                }
                            }
                        }
                    }
                    $content = ob_get_clean();
                }
            }
            $custom_fields['content'] = $content;
            return $custom_fields;
        }

        public function wp_dp_custom_fields_html_callback($listing_id = '', $view = '', $col_class = '') {
            global $post, $wp_dp_post_listing_types;
            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }

            if ( $listing_id != '' ) {
                $listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                $wp_dp_listing_type_cus_fields = $wp_dp_post_listing_types->wp_dp_types_custom_fields_array($listing_type);
                if ( is_array($wp_dp_listing_type_cus_fields) && isset($wp_dp_listing_type_cus_fields) && ! empty($wp_dp_listing_type_cus_fields) ) {
                    ob_start();

                    foreach ( $wp_dp_listing_type_cus_fields as $cus_fieldvar => $cus_field ) {
                        if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] <> '' ) {



                            $cus_field_value_arr = get_post_meta($listing_id, $cus_field['meta_key'], true);
                            $cus_field_label_arr = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_field_icon_arr = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_field_icon_group_arr = isset($cus_field['fontawsome_icon_group']) ? $cus_field['fontawsome_icon_group'] : 'default';

                            $cus_format = isset($cus_field['date_format']) ? $cus_field['date_format'] : '';
                            $type = isset($cus_field['type']) ? $cus_field['type'] : '';

                            if ( $type == 'dropdown' ) {
                                $drop_down_arr = array();
                                $cut_field_flag = 0;
                                foreach ( $cus_field['options']['value'] as $key => $cus_field_options_value ) {
                                    $drop_down_arr[$cus_field_options_value] = force_balance_tags($cus_field['options']['label'][$cut_field_flag]);
                                    $cut_field_flag ++;
                                }
                            }

                            if ( isset($cus_field_value_arr) && $cus_field_value_arr <> '' ) {
                                ?>
                                <li<?php echo wp_dp_cs_allow_special_char($col_class); ?>>
                                    <?php
                                    if ( isset($cus_field_icon_arr) && $cus_field_icon_arr <> '' && $view != 'view-5' ) {
                                        wp_enqueue_style('cs_icons_data_css_' . $cus_field_icon_group_arr);
                                        ?>
                                        <i class="<?php echo esc_html($cus_field_icon_arr) ?>"></i>
                                        <?php
                                    }
                                    if ( is_array($cus_field_value_arr) ) {
                                        
                                        $label_counter = true;
                                        foreach ( $cus_field_value_arr as $key => $single_value ) {
                                            if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type == 'date' ) {
                                                echo '<span class="field-label">' . esc_html($cus_field_label_arr) . '</span>';
                                            }
                                            ?>
                                           
                                                <?php
                                                if ( isset($cus_format) && $cus_format != '' && $single_value != '' ) {
                                                    echo date($cus_format, $single_value);
                                                } else if ( $type == 'dropdown' && isset($drop_down_arr[$single_value]) && $drop_down_arr[$single_value] != '' ) {
                                                    if ( $view == 'view-5' && $label_counter) {
                                                        echo '<strong class="field-label">' . esc_html($cus_field_label_arr) . '</strong>';
                                                        $label_counter =false;
                                                    } ?>
						 <span class="field-value">
						<?php
                                                    echo esc_html($drop_down_arr[$single_value]);
                                                } elseif ( $single_value != '' ) {
                                                    echo esc_html($single_value);
                                                }
                                                ?>
                                            </span>
                                            <?php
                                        }
                                        if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type != 'dropdown' && $type != 'date' ) {
                                            echo '<span class="field-label">' . esc_html($cus_field_label_arr) . '</span>';
                                        }
                                    } else {

                                        if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type == 'date' ) {
                                            echo '<span class="field-label">' . esc_html($cus_field_label_arr) . '</span>';
                                        }

                                        if ( isset($cus_format) && $cus_format != '' && $cus_field_value_arr != '' ) {
                                            echo '<span class="field-value">' . date($cus_format, $cus_field_value_arr) . '</span>';
                                        } else if ( $type == 'dropdown' && isset($drop_down_arr[$cus_field_value_arr]) && $drop_down_arr[$cus_field_value_arr] != '' ) {
                                            if ( $view == 'view-5' ) {
                                                echo '<strong class="field-label">' . esc_html($cus_field_label_arr) . '</strong>';
                                            }
                                            echo '<span class="field-value">' . esc_html($drop_down_arr[$cus_field_value_arr]) . '</span>';
                                        } elseif ( $cus_field_value_arr != '' ) {
                                            if ( $view == 'view-5' ) {
                                                echo '<strong class="field-label">' . esc_html($cus_field_label_arr) . '</strong>';
                                            }
                                            echo '<span class="field-value">' . esc_html($cus_field_value_arr) . '</span>';
                                        }

                                        if ( isset($cus_field_label_arr) && $cus_field_label_arr <> '' && $type != 'dropdown' && $type != 'date' ) {
                                            //echo '<span class="field-label">' . esc_html($cus_field_label_arr) . '</span>';
                                        }
                                    }
                                    ?>
                                </li>

                                <?php
                            }
                        }
                    }

                    $content = ob_get_clean();
                    
                    $listing_type_slug = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                    $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
                    $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;
                    $element_title = get_post_meta( $listing_type_id, 'wp_dp_listing_type_title_key_details', true);

                    
                    if ( $content != '' ) {
                        if ( $view == 'view-5' ) {
                            echo '<div class="key-details-holder">
                                <div class="element-title">
                                    <h3>' . $element_title . '</h3>
                                </div>';
                        }
                        echo '<div class="row">';
							echo '<ul class="categories-holder">';
							echo wp_dp_allow_special_char($content);
							echo '</ul>';
						echo '</div>';
                        if ( $view == 'view-5' ) {
                            echo '</div>';
                        }
                    }
                }
            }
        }

    }

    global $wp_dp_custom_fields;
    $wp_dp_custom_fields = new wp_dp_custom_fields_element();
}