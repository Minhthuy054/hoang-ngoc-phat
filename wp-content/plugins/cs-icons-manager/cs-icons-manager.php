<?php
/**
 * Plugin Name: CS Icons Manager
 * Plugin URI: http://themeforest.net/user/Chimpstudio/
 * Description: CS Icons Manager
 * Version: 1.9
 * Author: ChimpStudio
 * Author URI: http://themeforest.net/user/Chimpstudio/
 * @package CS Icons Manager
 *
 * @package CS Icons Manager
 */
// Direct access not allowed.
if ( ! defined('ABSPATH') ) {
    exit;
}

if ( ! class_exists('CS_Icons_Manager') ) {

    class CS_Icons_Manager {

        public $admin_notices;

        /**
         * Start construct Functions
         */
        public function __construct() {
            // Define constants
            define('CS_ICONS_MANAGER_PLUGIN_VERSION', '1.4');
            define('CS_ICONS_MANAGER_PLUGIN_DOMAIN', 'cs-icons-manager');
            define('CS_ICONS_MANAGER_PLUGIN_URL', WP_PLUGIN_URL . '/cs-icons-manager');
            define('CS_ICONS_MANAGER_CORE_DIR', WP_PLUGIN_DIR . '/cs-icons-manager');
            define('CS_ICONS_MANAGER_LANGUAGES_DIR', CS_ICONS_MANAGER_CORE_DIR . '/languages');

            $this->admin_notices = array();
            //admin notices
            add_action('admin_notices', array( $this, 'cs_icons_manager_notices_callback' ));

            // Initialize Addon
            add_action('init', array( $this, 'init_callback' ), 0);
            add_action('admin_menu', array( $this, 'cs_icons_manager_plugin_settings' ), 10);
            add_action('cs_import_icons', array( $this, 'cs_import_icons_handle' ));
            add_action('wp_ajax_cs_export_icons', array( $this, 'cs_export_icons_callback' ));
            add_action('wp_rem_plugin_db_structure_updater', array( $this, 'wp_rem_plugin_db_structure_updater_callback' ));

            register_activation_hook(__FILE__, array( $this, 'register_activation_hook_callback' ));
            $this->include_files();
			
			
        }
		

        /*
         * Include Recomended Files
         */

        public function include_files() {
            require_once 'classes/form-fields/class-form-fields.php';
            require_once 'classes/form-fields/class-html-fields.php';
            require_once 'assets/translate/class-strings.php';
            require_once 'classes/class-icons-uploader.php';
            require_once 'classes/class-icons-fields.php';
            require_once(ABSPATH . 'wp-admin/includes/screen.php');
        }

        /**
         * Initialize application, load text domain, enqueue scripts and bind hooks
         */
        public function init_callback() {

            $icons_path_updated = get_option('cs_icons_path_updated');
            if ( $icons_path_updated != 'yes' ) {
                $this->wp_rem_plugin_db_structure_updater_callback();
            }

            remove_filter('cs_icons_fields', 'icons_fields_callback', 10);
            // Add Plugin textdomain
            load_plugin_textdomain(CS_ICONS_MANAGER_PLUGIN_DOMAIN, false, CS_ICONS_MANAGER_LANGUAGES_DIR);
            // Enqueue JS   
            if ( is_admin() ) {
                wp_enqueue_style('cs-icons-manager-style', esc_url(CS_ICONS_MANAGER_PLUGIN_URL . '/assets/css/cs-icons-manager.css'));
                wp_enqueue_script('cs-icons-manager-script', esc_url(CS_ICONS_MANAGER_PLUGIN_URL . '/assets/scripts/cs-icons-manager-functions.js'), '', CS_ICONS_MANAGER_PLUGIN_VERSION, true);
                wp_localize_script('cs-icons-manager-script', 'cs_icons_manager', array(
                    'ajax_url' => esc_url(admin_url('admin-ajax.php')),
                ));
            }
            $icons_groups = get_option('cs_icons_groups');
            if ( ! empty($icons_groups) ) {
                foreach ( $icons_groups as $icon_key => $icon_obj ) {
                    if ( isset($icon_obj['status']) && $icon_obj['status'] == 'on' ) {
                        global $pagenow;
                        $post_type = !isset($_GET['post_type'])? 'post' : $_GET['post_type'];
//                        if( !($pagenow == 'edit.php' && $post_type == 'post') )
//                        temprary comment
//                            wp_register_style('cs_icons_data_css_' . $icon_key, $icon_obj['url'] . '/style.css');
//                        end temprary comment
                        $org_site_url = get_option('siteurl');
                        $icon_obj_url = str_replace( $org_site_url, site_url(), $icon_obj['url']);
                        
                        if(preg_match('/www/', $_SERVER['HTTP_HOST'])){
                          if(!preg_match('/www/', $icon_obj_url)){
                                $bits = parse_url($icon_obj_url);
                                $newHost = substr($bits["host"],0,4) !== "www."?"www.".$bits["host"]:$bits["host"];
                                $icon_obj_url = $bits["scheme"]."://".$newHost.(isset($bits["port"])?":".$bits["port"]:"").$bits["path"].(!empty($bits["query"])?"?".$bits["query"]:"");
                          }
                        }
                        wp_register_style('cs_icons_data_css_' . $icon_key, $icon_obj_url . '/style.css');
                        if ( is_admin() || $icon_key == 'default' ) {
                            wp_enqueue_style('cs_icons_data_css_' . $icon_key);
                        }
                    }
                }
            }
        }

        public function wp_rem_plugin_db_structure_updater_callback() {
            $upload_dir = wp_upload_dir();
            $destination_path = $upload_dir['path'] . '/cs-icons-manager';
            wp_mkdir_p($destination_path);
            $icons_groups = get_option('cs_icons_groups');
            $icons = array();
            if ( ! empty($icons_groups) && is_array($icons_groups) ) {
                foreach ( $icons_groups as $key => $val ) {
                    if ( $key == 'default' ) {
                        $icons[$key]['path'] = CS_ICONS_MANAGER_CORE_DIR . '/assets/default';
                        $icons[$key]['url'] = CS_ICONS_MANAGER_PLUGIN_URL . '/assets/default';
                        $icons[$key]['status'] = 'on';
                    } else {
                        $upload_dir = wp_upload_dir();
                        $icon_exists = $this->check_file_exists($upload_dir['path'] . '/cs-icons-manager/' . $key . '*/selection.json');
                        if ( ! empty($icon_exists) ) {
                            $icons[$key]['path'] = $upload_dir['path'] . '/cs-icons-manager/' . $key;
                            $icons[$key]['url'] = $upload_dir['url'] . '/cs-icons-manager/' . $key;
                            $icons[$key]['status'] = $val['status'];
                        }
                    }
                }
            } else {
                $icons = $icons_groups;
            }
            update_option('cs_icons_groups', $icons);
            update_option('cs_icons_path_updated', 'yes');
        }

        /**
         * Fetch and return version of the current plugin
         *
         * @return	string	version of this plugin
         */
        public static function get_plugin_version() {
            $plugin_data = get_plugin_data(__FILE__);
            return $plugin_data['Version'];
        }

        public function cs_icons_manager_notices_callback() {
            if ( isset($this->admin_notices) && ! empty($this->admin_notices) ) {
                foreach ( $this->admin_notices as $value ) {
                    echo $value;
                }
            }
        }

        /*
         * Adding Menu in Backend
         */

        public function cs_icons_manager_plugin_settings() {
		
            //add_menu_page(cs_icons_manager_text_srt('cs_icons_icons_manager'), cs_icons_manager_text_srt('cs_icons_icons_manager'), 'manage_options', 'cs_icons_manager_settings', array( &$this, 'cs_icons_manager_settings_callback' ), 'dashicons-nametag', 45);
            add_submenu_page('wp_dp_directory', cs_icons_manager_text_srt('cs_icons_icons_manager'), cs_icons_manager_text_srt('cs_icons_icons_manager'), 'manage_options', 'cs_icons_manager_settings', array( &$this, 'cs_icons_manager_settings_callback' ));
        }

        /*
         * Rendering Icons Manager Settings Page
         */

        public function cs_icons_manager_settings_callback() {
            global $cs_icons_html_fields, $cs_icons_form_fields;

            echo '<div class="wrap"><h2>' . cs_icons_manager_text_srt('cs_icons_icons_manager') . ' ';
            echo '<a href="javascript:;" class="add-new-h2 cs-icons-uploadMedia">' . cs_icons_manager_text_srt('cs_icons_upload_new_icon') . '</a>';
            echo '</h2>';

            $this->cs_export_icons();

            $cs_icons_opt_array = array(
                'id' => 'fonts_zip_rand',
                'std' => '',
            );

            $cs_icons_form_fields->cs_icons_form_hidden_render($cs_icons_opt_array);
            echo '<div class="cs-icons-msg"></div>';
            echo '<div class="cs-icons-manager-wrapper">';
            $this->cs_icons_list();
            echo '</div></div>';
            do_action('cs_icons_fields');
        }

        public function cs_export_icons() {
            if ( isset($_REQUEST['export']) && $_REQUEST['export'] == '1' ) {
                ?>
                <div class="export-icons-wrapper">
                    <div class="export-btn">
                        <a class="export-icons-btn" href="javascript:void(0);"><?php echo cs_icons_manager_text_srt('cs_icons_export_btn_label'); ?></a>
                        <a id="export-icons" class="export-icons" href="javascript:void(0);" style="display:none;" download=""><?php echo cs_icons_manager_text_srt('cs_icons_export_btn_label'); ?></a>
                    </div>
                </div>
                <?php
            }
        }

        /*
         * Listing all Icons
         */

        public function cs_icons_list() {
            global $cs_icons_html_fields, $cs_icons_form_fields;
            $icons_groups = get_option('cs_icons_groups');
			if ( ! empty($icons_groups) ) {
                foreach ( $icons_groups as $icons_key => $icons_obj ) {

                    $group_obj = $icons_groups[$icons_key];
                    $selection_path = $group_obj['url'];
                    $org_site_url = get_option('siteurl');
                    $selection_path = str_replace( $org_site_url, site_url(), $selection_path);
                    if(preg_match('/www/', $_SERVER['HTTP_HOST'])){
                      if(!preg_match('/www/', $selection_path)){
                            $bits = parse_url($selection_path);
                            $newHost = substr($bits["host"],0,4) !== "www."?"www.".$bits["host"]:$bits["host"];
                            $selection_path = $bits["scheme"]."://".$newHost.(isset($bits["port"])?":".$bits["port"]:"").$bits["path"].(!empty($bits["query"])?"?".$bits["query"]:"");
                      }
                    }
                    

                    wp_enqueue_style('cs_icons_css_' . $icons_key, $selection_path . '/style.css');
                    ?>
                    <div class="icon_set-Defaults metabox-holder cs-icons-manager-list" data-id="<?php echo esc_attr($icons_key); ?>">
                        <div class="postbox">
                            <h3 class="icon_font_name">
                                <strong><?php echo esc_html($icons_key); ?></strong>
                                <span class="fonts-count cs-count-icons" id="cs-count-<?php echo esc_html($icons_key); ?>">0</span>
                                <?php if ( $icons_key != 'default' ) { ?>
                                    <span class="fonts-count cs-group-remove"><?php echo cs_icons_manager_text_srt('cs_icons_delete'); ?></span>
                                    <?php
                                    $cs_icons_opt_array = array(
                                        'name' => '',
                                        'desc' => '',
                                        'hint_text' => '',
                                        'field_params' => array(
                                            'std' => $icons_obj['status'],
                                            'id' => 'enable_group' . $icons_key,
                                            'classes' => 'cs-icons-enable-group',
                                            'extra_atr' => 'data-group="' . $icons_key . '"',
                                        ),
                                    );
                                    $cs_icons_html_fields->cs_icons_checkbox_field($cs_icons_opt_array);
                                }
                                ?>
                            </h3>
                            <?php
                            echo '<div class="inside">
                                <div class="icon_actions"></div>
                                <div class="icon_search icons_list_' . $icons_key . ' cs-icons-list">
                                    <ul>
                                        <script>
                                            jQuery(document).ready(function ($) {
                                                   var html_response  = "";
                                                    $.ajax({
                                                        url: "' . $selection_path . '/selection.json",
                                                        type: \'GET\',
                                                        dataType: \'json\'
                                                    }).done(function (response) {
                                                        var classPrefix = response.preferences.fontPref.prefix;
                                                        jQuery("#cs-count-' . $icons_key . '").html(response.icons.length);
                                                        $.each(response.icons, function (i, v) {
                                                            var li_html = "";
                                                            li_html += "<li><i class=\'";
                                                            li_html += classPrefix+v.properties.name;
                                                            li_html += "\'></i></li>";
                                                            html_response   += li_html;
                                                        });
                                                        jQuery(".icons_list_' . $icons_key . ' ul").html(html_response);
                                                    });
                                            });
                                        </script>
                                    </ul>
                                </div>
                            </div>
                        </div></div>';
                        }
                    }
                }

                /*
                 * On activation of the plugin
                 */

                public function register_activation_hook_callback() {
                    $upload_dir = wp_upload_dir();
                    $destination_path = $upload_dir['path'] . '/cs-icons-manager';
                    wp_mkdir_p($destination_path);
                    $icons_groups = get_option('cs_icons_groups');
                    if ( ! isset($icons_groups['default']) || empty($icons_groups['default']) ) {
                        $new_group['default'] = array(
                            'path' => CS_ICONS_MANAGER_CORE_DIR . '/assets/default',
                            'url' => CS_ICONS_MANAGER_PLUGIN_URL . '/assets/default',
                            'status' => 'on'
                        );
                        if ( ! empty($icons_groups) ) {
                            $new_group = array_merge($new_group, $icons_groups);
                        }
                        update_option('cs_icons_groups', $new_group);
                    }
                }

                /*
                 * Import Icons
                 */

                public function cs_import_icons_handle($obj) {
                    global $wp_filesystem;
                    if ( $obj->icons_data_path != '' ) {
                        $icons = $wp_filesystem->get_contents($obj->icons_data_path);
                        $cs_icons = json_decode($icons, true);
                        $icons = array();
                        if ( ! empty($cs_icons) && is_array($cs_icons) ) {
                            foreach ( $cs_icons as $key => $val ) {
                                if ( $key == 'default' ) {
                                    $icons[$key]['path'] = CS_ICONS_MANAGER_CORE_DIR . '/assets/default';
                                    $icons[$key]['url'] = CS_ICONS_MANAGER_PLUGIN_URL . '/assets/default';
                                    $icons[$key]['status'] = 'on';
                                } else {
                                    $upload_dir = wp_upload_dir();
                                    $icons[$key]['path'] = $upload_dir['path'] . '/cs-icons-manager/' . $key;
                                    $icons[$key]['url'] = $upload_dir['url'] . '/cs-icons-manager/' . $key;
                                    $icons[$key]['status'] = $val['status'];
                                }
                            }
                        } else {
                            $icons = $cs_icons;
                        }
                        update_option('cs_icons_groups', $icons);
                        $obj->action_return = true;
                    } else {
                        $obj->action_return = false;
                    }
                }

                /*
                 * Export Icons
                 */

                public function cs_export_icons_callback() {
                    global $wp_filesystem;

                    $cs_icons_groups = get_option('cs_icons_groups');

                    $cs_icons_groups_fields = json_encode($cs_icons_groups, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                    $cs_upload_dir = CS_ICONS_MANAGER_CORE_DIR . '/assets/backups/';
                    $name = 'icons.json';
                    $cs_filename = trailingslashit($cs_upload_dir) . $name;
                    $cs_fileurl = CS_ICONS_MANAGER_PLUGIN_URL . '/assets/backups/' . $name;
                    if ( ! $wp_filesystem->put_contents($cs_filename, $cs_icons_groups_fields, FS_CHMOD_FILE) ) {
                        echo json_encode(array( 'type' => 'error', 'name' => $name, 'url' => $cs_fileurl ));
                    } else {
                        echo json_encode(array( 'type' => 'success', 'name' => $name, 'url' => $cs_fileurl ));
                    }
                    die();
                }

                public function check_file_exists($pattern, $flags = 0) {
                    $files = glob($pattern, $flags);
                    foreach ( glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir ) {
                        $files = array_merge($files, $this->check_file_exists($dir . '/' . basename($pattern), $flags));
                    }
                    return $files;
                }

            }

            global $cs_icons_manager;
            $cs_icons_manager = new CS_Icons_Manager();
        }
		
