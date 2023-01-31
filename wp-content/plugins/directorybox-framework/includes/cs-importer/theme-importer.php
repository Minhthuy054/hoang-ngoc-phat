<?php
/**
 * Control GUI and AJAX requests
 *
 * @since	1.2
 * @package	WordPress
 */
// If this file is called directly, abort.
if ( ! defined('ABSPATH') ) {
    wp_die;
}

set_time_limit(0); // Set time limit infinit.

add_action('admin_menu', 'wp_dp_cs_theme', 20);
if ( ! function_exists('wp_dp_cs_theme') ) {

    /**
     * Add Importer item to WP Admin Dashboard sidebar menu
     */
    function wp_dp_cs_theme() {
        add_submenu_page('wp_dp_directory', __('Import Demo Data', 'wp-dp-frame'), __('Import Demo Data', 'wp-dp-frame'), 'read', 'wp_dp_cs_demo_importer', 'wp_dp_cs_demo_importer');
    }

}

if ( ! function_exists('wp_dp_cs_demo_importer') ) {

    /**
     * Output GUI for Demo Importer
     */
    function wp_dp_cs_demo_importer() {//delete_option( 'item_purchase_code_verification' );die();
        $theme_id = THEME_ENVATO_ID;
        $theme_name = THEME_NAME;

        if ( function_exists('get_server_requirements') ) {
            $server_requirements = get_server_requirements();
        } else {
            wp_die(__('Server requirements not available.', 'wp-dp-frame'));
        }

        if ( function_exists('get_plugin_requirements') ) {
            $plugin_requirements = get_plugin_requirements();
        } else {
            wp_die(__('Plugin reuirements not available.', 'wp-dp-frame'));
        }

        if ( function_exists('get_demo_data_structure') ) {
            $demo_data_structure = get_demo_data_structure();
        } else {
            wp_die(__('Demo data structure not available.', 'wp-dp-frame'));
        }
        ?>
        <div style="margin-left: 15px;">
            <h2><?php _e('Import Demo Data', 'wp-dp-frame'); ?></h2>
            <form method="post">
                <div class="importer-wrapper container custom-importer">
                    <?php
                    // Check if API is available.
                    $wp_remote_post_args = array(
                        'timeout' => 50,
                    );
                    $response = wp_remote_post(REMOTE_API_URL, $wp_remote_post_args);
                    if ( is_wp_error($response) ) {
                        wp_die('<h2 class="error">' . __('Sorry, It seems that API server is not available. Please, contact theme owner and report this issue.', 'wp-dp-frame') . '</h2>');
                    }
                    ?>
                    <div class="importer-steps-wrapper row">
                        <div class="step step-1 col-lg-3 col-md-3 col-sm-12 active">
                            <div class="title-holder">
                                <span class="title-large"><?php _e('Landing', 'wp-dp-frame'); ?></span>
                                <span class="title-small"><?php _e('Server Requirements', 'wp-dp-frame'); ?></span>
                            </div>
                        </div>
                        <div class="step step-2 col-lg-3 col-md-3 col-sm-12">
                            <div class="title-holder">
                                <span class="title-large"><?php _e('Validate', 'wp-dp-frame'); ?></span>
                                <span class="title-small"><?php _e('Purchase Key', 'wp-dp-frame'); ?></span>
                            </div>
                        </div>
                        <div class="step step-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="title-holder">
                                <span class="title-large"><?php _e('Import', 'wp-dp-frame'); ?></span>
                                <span class="title-small"><?php _e('Existing Items', 'wp-dp-frame'); ?></span>
                            </div>
                        </div>
                        <div class="step step-4 col-lg-3 col-md-3 col-sm-12">
                            <div class="title-holder">
                                <span class="title-large"><?php _e('Done', 'wp-dp-frame'); ?></span>
                                <span class="title-small"><?php _e('Have Fun', 'wp-dp-frame'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="importer-steps-containers-wrapper importer-text row">
                        <div class="step-1-container">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h3><?php _e('Server Requirements', 'wp-dp-frame'); ?></h3>
                                <ul class="importer-requirement">
                                    <?php if ( ! empty($server_requirements) ) :
                                        
                                        ?>
                                        <?php foreach ( $server_requirements as $requirement ) : 
                                            $php_ini_help_text ='';
                                            if(!$requirement['is_met']){
                                                if( isset( $requirement['title'] ) && $requirement['title'] != 'UPLOADS_PERMISSIONS = 0755 ( Available 0777 )' ){
                                                    $php_ini_help_text = '<span class="importer-help-text">'.esc_html__('You can change the settings from your php.ini file, if you can not find the file or the changes are not effecting on the site please contact your hosting providers.','wp-dp-frame').'</span>';
                                                }
                                            } 
                                            ?>
                                            <li>
                                                <div class="pull-left">
                                                    <div class="text-holder">
                                                        <span class="text-bold text-uppercase"><?php echo $requirement['title']; ?></span>
                                                        <span class="details"><?php echo $requirement['description']; ?></span>
                                                        <?php echo ($php_ini_help_text);?>
                                                    </div>
                                                </div>
                                                <div class="pull-right">
                                                    <div class="importer-version">
                                                        <span><?php echo $requirement['version']; ?></span>
                                                        <i class="<?php echo $requirement['is_met'] ? 'icon-check-circle' : 'error icon-circle-with-cross'; ?>"></i>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <a href="http://chimpgroup.com/wp-demo/documentation/documentation/directorybox-documentation/" class="importer-btn">
                                            <i class="icon-book3"></i>
                                            <?php _e('Online Document', 'wp-dp-frame'); ?>
                                        </a>	
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <a href="http://chimpgroup.com/support/" class="importer-btn help">
                                            <i class="icon-group"></i>
                                            <?php _e('Need Help?', 'wp-dp-frame'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h3><?php _e('Plugin Requirements', 'wp-dp-frame'); ?></h3>
                                <ul class="importer-requirement">
                                    <?php if ( ! empty($plugin_requirements) ) : ?>
                                        <?php foreach ( $plugin_requirements as $requirement ) : ?>
                                            <li>
                                                <div class="pull-left">
                                                    <div class="text-holder">
                                                        <span class="text-bold text-uppercase"><?php echo $requirement['title']; ?></span>
                                                        <span class="details">
                                                            <?php echo $requirement['description']; ?>
                                                            <?php
                                                            if ( ! empty($requirement['new_version']) ) {
                                                                echo '<br><span class="error">' . __('There is a new version', 'wp-dp-frame') . ' ' . $requirement['new_version'] . ' ' . __('available', 'wp-dp-frame') . '.</span>';
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="pull-right">
                                                    <div class="importer-version">
                                                        <span><?php echo $requirement['version']; ?></span>
                                                        <i class="<?php echo $requirement['is_installed'] ? 'icon-check-circle' : 'error icon-circle-with-cross'; ?>"></i>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                                <ul class="importer-social-media">
                                    <li>
                                        <a data-original-title="facebook" href="https://www.facebook.com/chimpstudiothemes"><i class="icon-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a data-original-title="google" href="https://plus.google.com/117490960128710835976/posts"><i class="icon-google4"></i></a>
                                    </li>
                                    <li>
                                        <a data-original-title="twitter" href="https://twitter.com/ChimpThemes"><i class="icon-twitter"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="step-2-container validate-purchase-key hidden">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h3><?php _e('Purchase code check', 'wp-dp-frame'); ?></h3>
                                <p><?php _e('Please enter your item purchase code of ChimpStudio', 'wp-dp-frame'); ?></p>
                                <?php
                                $envato_purchase_code_verification = get_option('item_purchase_code_verification');
                                $verify_code = true;
                                if ( $envato_purchase_code_verification ) {
                                    $theme_id = THEME_ENVATO_ID;
                                    if( isset( $envato_purchase_code_verification['item_id'] ) && isset( $envato_purchase_code_verification['last_verification_time'] ) ){
                                        if ($theme_id == $envato_purchase_code_verification['item_id'] && $envato_purchase_code_verification['last_verification_time'] + 30 * 24 * 60 * 60 > time()) {
                                            $verify_code = false;
                                            if ( function_exists('wp_dp_update_demo_data') ) {
                                                wp_dp_update_demo_data();
                                            }
                                        }
                                    }
                                }
                                ?>
                                <?php if ( $verify_code ) : ?>
                                    <form role="form">
                                        <div class="control-group">
                                            <label for="item-purchase-code"><?php _e('Envato Provided Item Purchase Code', 'wp-dp-frame'); ?></label>
                                            <input type="text" name="item-purchase-code" id="item-purchase-code" class="form-contorl">
                                            <label class="purchase-code-error" style="color: #ff0000; display: none;"><?php _e('Please provide a valid Item Purchase Code', 'wp-dp-frame'); ?></label>
                                            <label class="purchase-code-exists-error" style="color: #ff0000; display: none;"><?php _e('The Purchase Code is being used on another site. Please deregister it from there and then try again.', 'wp-dp-frame'); ?></label>

                                            <label for="envato-email-address"><?php _e('Envato Provided Email Address', 'wp-dp-frame'); ?></label>
                                            <input type="text" name="envato-email-address" id="envato-email-address" class="form-contorl">
                                            <br>
                                        </div>
                                    </form>
                                <?php else : ?>
                                    <h3 style="color: #069c14;"><?php _e('It seems that, you have already verified purchase code. Proceed to next step', 'wp-dp-frame'); ?></h3>
                                    <input type="button" value="<?php _e('Deregister Purchase Code', 'wp-dp-frame'); ?>" class="importer-next-btn release-purchase-code"><br><br><br>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="step-3-container hidden">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="sub-step-1">
                                    <h3><?php _e('Please choose demo', 'wp-dp-frame'); ?></h3>
                                    <p><?php _e('Please select a demo to install demo data.', 'wp-dp-frame'); ?></p>
                                    <ul class="demos-list importer-list row">
                                        <?php if ( ! empty($demo_data_structure) ) : $counter = 0; ?>
                                            <?php foreach ( $demo_data_structure as $demo_item ) : $counter ++; ?>
                                                <li class="demo-data-item-wrapper col-lg-3 col-md-3 col-sm-6 col-xs-12" data-name="<?php echo $demo_item['slug']; ?>">
                                                    <div class="radiobox">
                                                        <input type="radio" id="demo-<?php echo $counter; ?>" value="" name="<?php echo $demo_item['name']; ?>">
                                                        <label for="demo-1">
                                                            <a href="#">
                                                                <img src="<?php echo $demo_item['image_url']; ?>" alt="<?php echo $demo_item['name']; ?>">
                                                            </a>
                                                        </label>
                                                    </div>
                                                    <span class="demo-title"><?php echo $demo_item['name']; ?></span>
                                                    <a href="#" class="btn-import"><i class="icon-check-circle"></i></a>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <h3><?php _e('No demo data\'s defined. :(', 'wp-dp-frame'); ?></h3>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <div class="sub-step-2 col-lg-5 col-md-5 col-sm-12 col-xs-12 hidden">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <h3><?php _e('Select what to import:', 'wp-dp-frame'); ?></h3>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <a class="unselect-all" href="javascript:void(0);" style="float: right; font-weight: bold;padding-top: 15px;"><?php _e('Unselect All', 'wp-dp-frame'); ?></a>
                                            <a class="select-all" href="javascript:void(0);" style="float: right; font-weight: bold;padding-top: 15px;"><?php _e('Select All', 'wp-dp-frame'); ?>&nbsp;/&nbsp;</a>
                                        </div>
                                    </div>
                                    <?php
                                    $is_wp_dp_cs_installed = isset($plugin_requirements['wp_dp_wp_directorybox_manager']['is_installed']) ? $plugin_requirements['wp_dp_wp_directorybox_manager']['is_installed'] : false;
                                    $is_cs_icons_manager_installed = isset($plugin_requirements['cs_icons_manager']['is_installed']) ? $plugin_requirements['cs_icons_manager']['is_installed'] : false;
                                    $is_rev_slider_installed = isset($plugin_requirements['rev_slider']['is_installed']) ? $plugin_requirements['rev_slider']['is_installed'] : false;
                                    ?>
                                    <ul class="wp-dp-select-demo">
                                        <li class="content-chk-wrapper following-pages hidden">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-wp-content" value="content" name="chk-wp-content" class="checkbox-data-import-type">
                                                <label for="chk-wp-content" class="checkbox-data-import-type"><?php _e('WP Content ( Pages, Posts, etc. )', 'wp-dp-frame'); ?></label>
                                            </div>
                                        </li>
                                        <li class="navitems-chk-wrapper following-pages hidden">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-wp-navitems" value="navitems" name="chk-wp-navitems" class="checkbox-data-import-type">
                                                <label for="chk-wp-navitems" class="checkbox-data-import-type"><?php _e('Navigation Menu Items', 'wp-rem-frame'); ?></label>
                                            </div>
                                        </li>
                                        <li class="media-attachments-chk-wrapper following-pages hidden">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-wp-media-attachments" value="media_attachments" name="chk-wp-media-attachments" class="checkbox-data-import-type">
                                                <label for="chk-wp-media-attachments" class="checkbox-data-import-type"><?php _e('Media Attachments', 'wp-rem-frame'); ?></label>
                                            </div>
                                        </li>
                                        <li class="options-chk-wrapper hidden">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-theme-options" value="theme_options" name="chk-theme-options" class="checkbox-data-import-type">
                                                <label for="chk-theme-options" class="checkbox-data-import-type"><?php _e('Theme Options', 'wp-dp-frame'); ?></label>	
                                            </div>
                                        </li>
                                        <li class="plugin-chk-wrapper hidden">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-plugin-options" value="plugin_options" name="chk-plugin-options" class="checkbox-data-import-type" <?php echo $is_wp_dp_cs_installed ? '' : 'disabled="disabled"'; ?>>
                                                <label for="chk-plugin-options" class="checkbox-data-import-type">
                                                    <?php _e('Plugin Options', 'wp-dp-frame'); ?>
                                                    <?php echo $is_wp_dp_cs_installed ? '' : '<br>' . __('Please install WP Directorybox Manager Plugin.', 'wp-dp-frame'); ?>
                                                </label>	
                                            </div>
                                        </li>
                                        <li class="widgets-chk-wrapper hidden">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-widgets" value="widgets" name="chk-widgets" class="checkbox-data-import-type">
                                                <label for="chk-widgets" class="checkbox-data-import-type"><?php _e('Widgets', 'wp-dp-frame'); ?></label>
                                            </div>
                                        </li>
                                        <li class="fonts-chk-wrapper hidden">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-fonts" value="fonts" name="chk-fonts" class="checkbox-data-import-type">
                                                <label for="chk-fonts" class="checkbox-data-import-type"><?php _e('Fonts', 'wp-dp-frame'); ?></label>	
                                            </div>
                                        </li>
                                        <li class="icons-chk-wrapper hidden">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-icons" value="icons" name="chk-icons" class="checkbox-data-import-type" <?php echo $is_cs_icons_manager_installed ? '' : 'disabled="disabled"'; ?>>
                                                <label for="chk-icons" class="checkbox-data-import-type">
                                                    <?php _e('Icons', 'wp-dp-frame'); ?>
                                                    <?php echo $is_cs_icons_manager_installed ? '' : '<br>' . __('Please install CS Icons Manager Plugin.', 'wp-dp-frame'); ?>
                                                </label>	
                                            </div>
                                        </li>
                                        <li class="menus-chk-wrapper hidden">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-menus" value="menus" name="chk-menus" class="checkbox-data-import-type">
                                                <label for="chk-menus" class="checkbox-data-import-type"><?php _e('Menus', 'wp-dp-frame'); ?></label>
                                            </div>
                                        </li>
                                        <li class="users-chk-wrapper hidden <?php echo $is_wp_dp_cs_installed ? '' : 'error'; ?>">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-users" value="users" name="chk-users" class="checkbox-data-import-type" <?php echo $is_wp_dp_cs_installed ? '' : 'disabled="disabled"'; ?>>
                                                <label for="chk-users" class="checkbox-data-import-type">
                                                    <?php _e('Users', 'wp-dp-frame'); ?>
                                                    <?php echo $is_wp_dp_cs_installed ? '' : '<br>' . __('Please install WP Directorybox Manager Plugin.', 'wp-dp-frame'); ?>
                                                </label>
                                            </div>
                                        </li>
                                        <li class="sliders-chk-wrapper hidden <?php echo $is_rev_slider_installed ? '' : 'error'; ?>">
                                            <div class="radiobox">
                                                <input type="checkbox" id="chk-rev-slider" value="rev_slider" name="chk-rev-slider" class="checkbox-data-import-type" <?php echo $is_rev_slider_installed ? '' : 'disabled="disabled"'; ?>>
                                                <label for="chk-rev-slider" class="checkbox-data-import-type">
                                                    <?php _e('Revolution Slider', 'wp-dp-frame'); ?>
                                                    <?php echo $is_rev_slider_installed ? '' : '<br>' . __('Please install Revolution Slider Plugin.', 'wp-dp-frame'); ?>
                                                </label>
                                            </div>
                                            <div class="overwrite-options" <?php echo $is_rev_slider_installed ? '' : 'style="display:none;"'; ?>>
                                                <h4><?php _e('Select what to do if following things exist:', 'wp-dp-frame'); ?></h4>
                                                <ul class="radio-custom-holder">
                                                    <li class="animations-overwrite">
                                                        <span><?php _e('Custom Animations', 'wp-dp-frame'); ?></span>
                                                        <input type="radio" id="rdio-custom-animations-overwrite" name="rdio-custom-animations" value="true" checked="checked">
                                                        <label for="rdio-custom-animations-overwrite"><?php _e('Overwrite', 'wp-dp-frame'); ?></label>
                                                        <input type="radio" id="rdio-custom-animations-append" name="rdio-custom-animations" value="false">
                                                        <label for="rdio-custom-animations-append"><?php _e('Append', 'wp-dp-frame'); ?></label>
                                                    </li>
                                                    <li class="animations-overwrite">
                                                        <span><?php _e('Custom Navigations', 'wp-dp-frame'); ?></span>
                                                        <input type="radio" id="rdio-custom-navigations" name="rdio-custom-navigations" value="true" checked="checked"> 
                                                        <label for="rdio-custom-navigations"><?php _e('Overwrite', 'wp-dp-frame'); ?></label>
                                                        <input type="radio" id="rdio-custom-navigations-append" name="rdio-custom-navigations" value="false"> 
                                                        <label for="rdio-custom-navigations-append"><?php _e('Append', 'wp-dp-frame'); ?></label>
                                                    </li>
                                                    <li class="animations-overwrite">
                                                        <span><?php _e('Static Styles', 'wp-dp-frame'); ?></span>
                                                        <input type="radio" id="rdio-static-styles" name="rdio-static-styles" value="true" checked="checked">
                                                        <label for="rdio-static-styles"><?php _e('Overwrite', 'wp-dp-frame'); ?></label>
                                                        <input type="radio" id="rdio-static-styles-1" name="rdio-static-styles" value="false">
                                                        <label for="rdio-static-styles-1"><?php _e('Append', 'wp-dp-frame'); ?></label>
                                                        <input type="radio" id="rdio-static-styles-2" name="rdio-static-styles" value="none">
                                                        <label for="rdio-static-styles-2"><?php _e('Ignore', 'wp-dp-frame'); ?></label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="sub-step-3 hidden">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <h3><?php _e('Importing Demo Data:', 'wp-dp-frame'); ?></h3>
                                        <ul class="list-process-queue">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-4-container importe-success hidden">
                            <div class="col-lg-12">
                                <i class="icon-check-circle"></i>
                                <h5><?php _e('Congratulations!!!', 'wp-dp-frame'); ?></h5>
                                <h1><?php _e('Your demo data has been successfully imported.', 'wp-dp-frame'); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="cs-importer-controls-wrapper">
                        <div class="control-group pull-left">
                            <input type="button" value="<?php _e('Previous Step', 'wp-dp-frame'); ?>" class="btn-prev-step importer-next-btn pull-left">
                        </div>
                        <div class="control-group pull-right">
                            <img src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" alt="loading" class="importer-ajax-loader pull-left hidden" style="width: 15px; padding: 5px;">
                            <input type="button" value="<?php _e('Next Step', 'wp-dp-frame'); ?>" class="btn-next-step importer-next-btn pull-right">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            (function ($) {
                $(function ( ) {
                    var current_step = 1;
                    var current_sub_step = 1;
                    var import_error_count = 0;
                    var is_purchase_code_verified = false;
                    var is_purchase_code_already_verified = <?php echo $verify_code ? 'false' : 'true'; ?>;
                    var is_urls_fetched = false;
                    var selected_demo = "";
                    var urls = [];

                    function change_step_callback(elem) {
                        change_step = true;
                        if (current_step == 1) {
                            // Show previous button.
                            $(".btn-prev-step").show();
                        } else if (current_step == 2) {
                            if (is_purchase_code_verified || is_purchase_code_already_verified) {
                                change_step = true;
                            } else if ($("input[name='item-purchase-code']").val() != "") {
                                $(".importer-ajax-loader").show();
                                $(".purchase-code-error").hide();
                                $(".purchase-code-exists-error").hide();
                                // Verify item purchase code.
                                $.ajax({
                                    "method": "post",
                                    "url": "<?php echo admin_url('admin-ajax.php'); ?>",
                                    "data": {
                                        "action": "wp_dp_cs_import_demo_data_callback",
                                        "action_type": "verify_purchase_code",
                                        "item_purchase_code": $("input[name='item-purchase-code']").val(),
                                        "envato_email_address": $("input[name='envato-email-address']").val(),
                                    },
                                    "dataType": "json",
                                    "success": function (data) {
                                        if (data.success == true) {
                                            is_purchase_code_verified = true;
                                            change_step_callback(elem);
                                            $(".importer-ajax-loader").hide();
                                        } else {
                                            if (data.message == 'already_exists') {
                                                $(".purchase-code-exists-error").show();
                                            } else {
                                                $(".purchase-code-error").show();
                                            }
                                            $(".importer-ajax-loader").hide();
                                        }
                                    }
                                });
                                // Do not change step until item purchase code is not verified.
                                change_step = false;
                            } else {
                                alert("<?php echo __('Please provide product purchase code.', 'wp-dp-frame'); ?>");
                                change_step = false;
                            }
                        } else if (current_step == 3) {
                            if (selected_demo == "") {
                                alert("<?php echo __('Please select a demo.', 'wp-dp-frame'); ?>");
                                change_step = false;
                            } else {
                                if (current_sub_step == 1) {

                                    if (!is_urls_fetched) {
                                        $(".importer-ajax-loader").show();
                                        $(".purchase-code-error").hide();
                                        $(".purchase-code-exists-error").hide();
                                        // Get demo data urls
                                        $.ajax({
                                            "method": "post",
                                            "url": "<?php echo admin_url('admin-ajax.php'); ?>",
                                            "data": {
                                                "action": "wp_dp_cs_import_demo_data_callback",
                                                "action_type": "get_demo_data_urls",
                                                "item_purchase_code": $(".item-purchase-code").val(),
                                                "item_demo_data_slug": selected_demo
                                            },
                                            "dataType": "json",
                                            "success": function (data) {
                                                if (data.success == true) {
                                                    // Show only those items for whom demo data is available.
                                                    urls = data.output.urls;
                                                    $.each(urls, function (key, value) {
                                                        var wrapper = $("." + value + "-chk-wrapper");
                                                        wrapper.show();
                                                        var elem = wrapper.find('input[type="checkbox"]');
                                                        elem.prop("checked", elem.is(":enabled"));
                                                    });
                                                    is_urls_fetched = true;

                                                    // Remove existing conflicted pages.
                                                    $(".overwrite-options", $(".content-chk-wrapper")).remove();

                                                    // Show conflicted pages.
                                                    $(".content-chk-wrapper").append(data.output.conflicted_pages);

                                                    $(".sub-step-1").hide(function () {
                                                        $(".sub-step-2").show( );
                                                    });
                                                    current_sub_step++;
                                                    $(".importer-ajax-loader").hide();
                                                } else {
                                                    $(".importer-ajax-loader").hide();
                                                }
                                            }
                                        });
                                    }
                                    change_step = false;
                                } else if (current_sub_step == 2) {
                                    var data_types = {
                                        "content": ["content", "WP Content ( posts, pages, etc. )"],
                                        "navitems": ["navitems", "Navigation Menu Items"],
                                        "media_attachments": ["media_attachments", "Media Attachments"],
                                        "theme_options": ["options", "Theme Options"],
                                        "fonts": ["fonts", "Fonts"],
                                        "icons": ["icons", "Icons"],
                                        "plugin_options": ["plugins", "Plugin Options"],
                                        "widgets": ["widgets", "Widgets"],
                                        "menus": ["menus", "Menus"],
                                        "users": ["users", "Users"],
                                        "rev_slider": ["rev_slider", "Revolution Slider"]
                                    };
                                    var queue = [];
                                    $(".checkbox-data-import-type:checked").each(function (index, elem) {
                                        var label = $(elem).val();
                                        var item = data_types[ label ];
                                        if ("content" == label) {
                                            var conflicted_pages = [];
                                            $(".chk-conflicted-pages:checked").each(function (index, elem) {
                                                conflicted_pages.push($(elem).val());
                                            });
                                            item.push(conflicted_pages);
                                        } else if ("rev_slider" == label) {
                                            var slider_options = [];
                                            slider_options.push($("input[name='rdio-custom-animations']:checked").val());
                                            slider_options.push($("input[name='rdio-custom-navigations']:checked").val());
                                            slider_options.push($("input[name='rdio-static-styles']:checked").val());

                                            item.push(slider_options);
                                        }
                                        queue.push(item);
                                    });

                                    // check there should be at least one item to import
                                    if (queue.length < 1) {
                                        alert("<?php echo __('Please select at least one item to import.', 'wp-dp-frame'); ?>");
                                        change_step = false;
                                    } else {
                                        $(".sub-step-2").hide(function () {
                                            $(".sub-step-3").show();
                                        });

                                        $.each(queue, function (index, value) {
                                            $(".list-process-queue").append('<li class="' + value[0] + '-item"><div class="pull-left"><span class="message">Importing ' + value[1] + '...</span></div><div class="pull-right"><i class="item-status waiting"></i></div></li>');
                                        });

                                        $(elem).hide( );
                                        $(".btn-prev-step").hide();
                                        process_importer_queue(queue);
                                        change_step = false;
                                    }
                                }
                            }
                        } else if (current_step == 4) {
                            current_step--;
                            change_step = true;
                        }

                        // Change step.
                        if (change_step) {
                            $(".step-" + current_step).removeClass("active");
                            $(".step-" + current_step + "-container").fadeOut(function () {
                                current_step += 1;
                                $(".step-" + current_step).addClass("active");
                                $(".step-" + current_step + "-container").fadeIn();
                            });
                        }
                    }

                    $(".btn-next-step").click(function () {
                        change_step_callback(this);
                    });

                    // Hide previous button on first step.
                    $(".btn-prev-step").hide( );

                    $(".btn-prev-step").click(function () {
                        var change_step = false;
                        if (current_step == 1 || current_step == 2) {
                            change_step = true;
                        } else if (current_step == 3) {
                            if (current_sub_step == 1) {
                                is_purchase_code_verified = false;
                                change_step = true;
                            } else if (current_sub_step == 2) {
                                is_urls_fetched = false;
                                current_sub_step--;
                                $(".sub-step-2").hide(function ( ) {
                                    $(".sub-step-1").show();
                                });
                            }
                        }


                        // Change step.
                        if (change_step) {
                            $(".step-" + current_step).removeClass("active");
                            $(".step-" + current_step + "-container").fadeOut(function ( ) {
                                current_step--;
                                $(".step-" + current_step).addClass("active");
                                $(".step-" + current_step + "-container").fadeIn( );
                                if (current_step == 1) {
                                    $(".btn-prev-step").hide();
                                }
                            });
                        }
                    });

                    $(".demo-data-item-wrapper").click(function () {
                        selected_demo = $(this).data("name");
                        return false;
                    });

                    function process_importer_queue(queue) {

                        item = queue.shift( );
                        data_type_name = item[0];
                        data_type_label = item[1];
                        other_data = "";
                        if ("content" == data_type_name || "rev_slider" == data_type_name) {
                            other_data = item[2];
                        }
                        var data = {
                            "action": "wp_dp_cs_import_demo_data_callback",
                            "action_type": "import_data",
                            "selected_demo": selected_demo,
                            "import_type": data_type_name,
                            "other_data": other_data,
                        };

                        $(".item-status", $(".list-process-queue li." + data_type_name + "-item")).removeClass("waiting").addClass("processing");
                        // Import data one by one from queue.
                        jQuery.ajax({
                            "type": "POST",
                            "url": "<?php echo admin_url('admin-ajax.php'); ?>",
                            "data": data,
                            "dataType": "json",
                            "success": function (data) {
                                var parentNode = $(".list-process-queue li." + data_type_name + "-item");

                                if (data.status == true) {
                                    $(".item-status", parentNode).removeClass("processing").addClass("icon-check-circle done");
                                    $(".message", parentNode).text(data_type_label + " successfully imported.");
                                } else {
                                    import_error_count++;
                                    $(".item-status", parentNode).removeClass("processing").addClass("error icon-circle-with-cross");
                                    $(".message", parentNode).text("Unable to import " + data_type_label);
                                }

                                if (queue.length > 0) {
                                    process_importer_queue(queue);
                                } else {
                                    add_to_active_themes();
                                    if (import_error_count < 1) {
                                        current_step++;
                                        change_step_callback(this);
                                    }
                                }
                            },
                            "error": function (xhr, ajaxOptions, thrownError) {
                                var parentNode = $(".list-process-queue li." + data_type_name + "-item");
                                $(".item-status", parentNode).removeClass("processing").addClass("error icon-circle-with-cross");
                                $(".message", parentNode).text("Unable to import " + data_type_label);
                                import_error_count++;
                                if (queue.length > 0) {
                                    process_importer_queue(queue);
                                }
                            }
                        });
                    }


                    function add_to_active_themes() {
                        $.ajax({
                            "method": "post",
                            "url": "<?php echo admin_url('admin-ajax.php'); ?>",
                            "data": {
                                "action": "wp_dp_add_to_active_themes",
                                "selected_demo": selected_demo,
                            },
                            "dataType": "json",
                            "success": function (data) {
                                console.log(data);
                            }
                        });
                    }

                    $('.release-purchase-code').click(function ( ) {
                        $.ajax({
                            "method": "post",
                            "url": "<?php echo admin_url('admin-ajax.php'); ?>",
                            "data": {
                                "action": "wp_dp_release_purchase_code",
                            },
                            "dataType": "json",
                            "success": function (data) {
                                location.reload();
                            }
                        });
                    });

                    $('.importer-list li').click(function ( ) {
                        $(this).addClass('importer-list-active').siblings( ).removeClass('importer-list-active');
                    });

                    $("#chk-wp-content,#chk-rev-slider").bind("change", function ( ) {
                        $(this).parent( ).parent( ).find(".overwrite-options").toggle($(this).is(":checked"));
                    });

                    $("#setting-error-tgmpa, #message, .notice").hide( );

                    jQuery('.sub-step-2 .select-all').click(function () {
                        //jQuery(this).hide();
                        jQuery('.sub-step-2 .unselect-all').show();
                        jQuery('.wp-dp-select-demo').find('input:checkbox').each(function () {
                            var disabled = jQuery(this).attr('disabled');
                            if (disabled !== 'disabled') {
                                jQuery(this).attr('checked', true);
                                jQuery(this).change();
                            }
                        });
                    });
                    jQuery('.sub-step-2 .unselect-all').click(function () {
                        //jQuery(this).hide();
                        jQuery('.sub-step-2 .select-all').show();
                        jQuery('.wp-dp-select-demo').find('input:checkbox').each(function () {
                            var disabled = jQuery(this).attr('disabled');
                            if (disabled !== 'disabled') {
                                jQuery(this).attr('checked', false);
                                jQuery(this).change();
                            }
                        });
                    });

                });
            })(jQuery);
        </script>
        <?php
    }

}
if ( ! function_exists('wp_dp_cs_import_demo_data_callback') ) {
    add_action('wp_ajax_wp_dp_cs_import_demo_data_callback', 'wp_dp_cs_import_demo_data_callback');

    /**
     * Handle AJAX import demo data calls
     */
    function wp_dp_cs_import_demo_data_callback() {
        $remote_api_url = REMOTE_API_URL;
        $theme_name = THEME_NAME;
        $action_type = isset($_POST['action_type']) ? $_POST['action_type'] : '';
        if ( 'import_data' == $action_type ) {
            if ( ! class_exists('wp_dp_cs_Data_Importer') ) {
                require_once wp_dp_framework::plugin_dir() . 'includes/cs-importer/cs-data-importer.php';
            }

            $selected_demo = isset($_POST['selected_demo']) ? $_POST['selected_demo'] : '';
            $import_type = isset($_POST['import_type']) ? $_POST['import_type'] : '';

            if ( empty($selected_demo) || empty($import_type) ) {
                echo __('Incomplete requested parameters', 'wp-dp-frame');
                wp_die();
            }

            $envato_purchase_code_verification = get_option('item_purchase_code_verification');

            if ( ! empty($envato_purchase_code_verification) ) {
                $urls = json_decode($envato_purchase_code_verification['urls'], true);

                if ( isset($urls[$selected_demo]) ) {
                    $urls = $urls[$selected_demo];
                } else {
                    echo json_encode(array( 'success' => false, 'message' => __('Sorry, i don\'t have URLs to import for this demo.', 'wp-dp-frame') ));
                    wp_die();
                }
            } else {
                echo json_encode(array( 'success' => false, 'message' => __('Sorry, i don\'t have URLs to import.', 'wp-dp-frame') ));
                wp_die();
            }

            $wp_dp_cs_importer = new wp_dp_cs_Data_Importer();
            $wp_dp_cs_importer->demo_data_name = $selected_demo;

            if ( 'content' == $import_type ) {
                // Delete conflicted pages if any selected by user.
                $selected_conflicted_pages = isset($_POST['other_data']) ? $_POST['other_data'] : array();
                foreach ( $selected_conflicted_pages as $key => $val ) {
                    // Get post by title.
                    $page_to_be_deleted = get_page_by_title($val);
                    if ( $page_to_be_deleted ) {
                        wp_delete_post($page_to_be_deleted->ID, true);
                    }
                }

                $wp_dp_cs_importer->homepage_slug = $wp_dp_cs_importer->demo_data_name . '-home';
                $wp_dp_cs_importer->wp_data_path = isset($urls['content']) ? $urls['content'] : '';
                $wp_dp_cs_importer->is_content = true;
            } elseif ( 'navitems' == $import_type ) {
                $wp_dp_cs_importer->wp_data_path = isset($urls['navitems']) ? $urls['navitems'] : '';
                $wp_dp_cs_importer->is_navitems = true;
            } elseif ( 'media_attachments' == $import_type ) {
                $wp_dp_cs_importer->wp_data_path = isset($urls['media-attachments']) ? $urls['media-attachments'] : '';
                $wp_dp_cs_importer->attachments_path = isset($urls['attachments']) ? $urls['attachments'] : '';
                $wp_dp_cs_importer->is_attachments_zip = true;
                $wp_dp_cs_importer->is_media_attachments = true;
            } elseif ( 'widgets' == $import_type ) {
                $wp_dp_cs_importer->widget_data_path = isset($urls['widgets']) ? $urls['widgets'] : '';
                $wp_dp_cs_importer->is_widgets = true;
            } elseif ( 'options' == $import_type ) {
                $wp_dp_cs_importer->theme_options_data_path = isset($urls['options']) ? $urls['options'] : '';
                $wp_dp_cs_importer->is_theme_options = true;
            } elseif ( 'fonts' == $import_type ) {
                $wp_dp_cs_importer->fonts_data_path = isset($urls['fonts']) ? $urls['fonts'] : '';
                $wp_dp_cs_importer->is_fonts = true;
            } elseif ( 'icons' == $import_type ) {
                $wp_dp_cs_importer->icons_data_path = isset($urls['icons']) ? $urls['icons'] : '';
                $wp_dp_cs_importer->is_icons = true;
            } elseif ( 'users' == $import_type ) {
                $wp_dp_cs_importer->users_data_path = isset($urls['users']) ? $urls['users'] : '';
                $wp_dp_cs_importer->is_users = true;
            } elseif ( 'menus' == $import_type ) {
                $wp_dp_cs_importer->menus_data_path = isset($urls['menus']) ? $urls['menus'] : '';
                $wp_dp_cs_importer->is_menus = true;
            } elseif ( 'plugins' == $import_type ) {
                $wp_dp_cs_importer->plugins_data_path = isset($urls['plugin']) ? $urls['plugin'] : '';
                $wp_dp_cs_importer->is_plugins = true;
            } elseif ( 'rev_slider' == $import_type ) {
                $wp_dp_cs_importer->sliders_data_path = isset($urls['sliders']) ? $urls['sliders'] : '';
                $wp_dp_cs_importer->is_sliders = true;
                $wp_dp_cs_importer->sliders_options = isset($_POST['other_data']) ? $_POST['other_data'] : array( false, false, false );
            }

            $wp_dp_cs_importer->import();
        } elseif ( 'verify_purchase_code' == $action_type ) {
            $envato_purchase_code_verification = get_option('item_purchase_code_verification');
            $item_purchase_code = isset($_POST['item_purchase_code']) ? $_POST['item_purchase_code'] : '';
            $envato_email_address = isset($_POST['envato_email_address']) ? $_POST['envato_email_address'] : '';
            $envato_purchase_code_verification['item_purchase_code'] = isset( $envato_purchase_code_verification['item_purchase_code'] )? $envato_purchase_code_verification['item_purchase_code'] : 0;
            $envato_purchase_code_verification['last_verification_time'] = isset( $envato_purchase_code_verification['last_verification_time'] )? $envato_purchase_code_verification['item_purchase_code'] : 0;

            $validate_purchase_code_now = false;
            if ( $envato_purchase_code_verification ) {
                // If last verfication is past 30 days ago then do that again.
                if( isset( $envato_purchase_code_verification['item_purchase_code'] ) && isset( $envato_purchase_code_verification['last_verification_time'] )){
                if (
                        $item_purchase_code == $envato_purchase_code_verification['item_purchase_code'] && $envato_purchase_code_verification['last_verification_time'] + 30 * 24 * 60 * 60 > time()
                ) {
                    $success = true;
                } else {
                    $validate_purchase_code_now = true;
                }
                }
            } else {
                $validate_purchase_code_now = true;
            }
            $success = false;



            $url = $remote_api_url;

            $verify_post_data = array(
                'action' => 'check_purchase_code',
                'theme_puchase_code' => $item_purchase_code,
                'site_url' => site_url(),
            );
            $verify_response = wp_remote_post($url, array( 'body' => $verify_post_data ));

            if ( isset($verify_response['body']) && $verify_response['body'] < 1 ) {


                // Validate purchase code now if it is required.
                if ( $validate_purchase_code_now ) {
                    $post_data = array(
                        'action' => 'verify_purchase_code',
                        'item_purchase_code' => $item_purchase_code,
                        'item_id' => THEME_ENVATO_ID,
                        'theme_name' => $theme_name,
                    );

                    $response = wp_remote_post($url, array( 'body' => $post_data ));
                    if ( is_wp_error($response) ) {
                        $success = false;
                    } else {
                        $body = json_decode($response['body'], true);


                        if ( 'true' == $body['success'] ) {
                            $data_for_option = [
                                'last_verification_time' => time(),
                                'item_puchase_code' => $item_purchase_code,
                                'envato_email_address' => $envato_email_address,
                                'item_name' => $theme_name,
                                'item_id' => THEME_ENVATO_ID,
                                'supported_until' => $body['supported_until'],
                                'urls' => json_encode($body['urls']),
                            ];
                            if ( $envato_purchase_code_verification ) {
                                update_option('item_purchase_code_verification', $data_for_option);
                            } else {
                                add_option('item_purchase_code_verification', $data_for_option);
                            }
                            $success = true;
                        } else {
                            $success = false;
                        }
                    }
                } else {
                    $success = false;
                }

				echo json_encode(array( 'success' => $success ));
            } else {
                echo json_encode(array( 'success' => $success, 'message' => 'already_exists' ));
            }
        } elseif ( 'get_demo_data_urls' == $action_type ) {
            $envato_purchase_code_verification = get_option('item_purchase_code_verification');
            $demo_data_name = isset($_POST['item_demo_data_slug']) ? $_POST['item_demo_data_slug'] : '';
            $output = array();
            if ( ! empty($envato_purchase_code_verification) ) {
                $urls = json_decode($envato_purchase_code_verification['urls'], true);
                if ( isset($urls[$demo_data_name]) ) {
                    $output['urls'] = array_keys($urls[$demo_data_name]);
                    $output['conflicted_pages'] = wp_dp_cs_get_importer_conflicted_pages($demo_data_name);
                }
            }
            $is_success = ( empty($output) === false );
            echo json_encode(array( 'success' => $is_success, 'output' => $output ));
        } else {
            echo __('Invalid action type.', 'wp-dp-frame');
        }
        wp_die();
    }

}
if ( ! function_exists('wp_dp_cs_get_importer_conflicted_pages') ) {

    /**
     * Return conflicted pages for the demo data in importer
     *
     * @param	string	$demo_data_name	demo data type/name.
     * @return	string	a string containing all conflicted pages HTML generated.
     */
    function wp_dp_cs_get_importer_conflicted_pages($demo_data_name) {
        $output = '';
        $confliced_pages_file = wp_dp_framework::plugin_dir() . 'includes/cs-importer/conflicts.php';
        if ( file_exists($confliced_pages_file) ) {
            require_once $confliced_pages_file;
            $conflicted_pages = isset( $conflicted_pages[$demo_data_name] )? $conflicted_pages[$demo_data_name] : array();
	    if(isset($conflicted_pages) && $conflicted_pages != '' && is_array($conflicted_pages)){
            foreach ( $conflicted_pages as $key => $name ) {
                if ( get_page_by_title($name) == null ) {
                    unset($conflicted_pages[$key]);
                }
	    }}
        } else {
            $conflicted_pages = array();
        }
        if ( ! empty($conflicted_pages) ) {
            ob_start();
            ?>
            <div class="overwrite-options">
                <h4 style="font-weight: bold;">Either rename following pages or Select Pages to be deleted on conflict:</h4>
                <ul>
                    <?php foreach ( $conflicted_pages as $key => $val ) : ?>
                        <li>
                            <input type="checkbox" id="<?php echo $val; ?>" class="chk-conflicted-pages" name="conflicted_pages[]" value="<?php echo $val; ?>" checked="checked">
                            <label for="<?php echo $val; ?>"><?php echo $val; ?></label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
            $output = ob_get_contents();
            ob_end_clean();
        }
        return $output;
    }

}

if ( ! function_exists('wp_dp_update_demo_data') ) {

    function wp_dp_update_demo_data() {
        $envato_purchase_code_verification = get_option('item_purchase_code_verification');
        $item_purchase_code = isset($envato_purchase_code_verification['item_purchase_code']) ? $envato_purchase_code_verification['item_purchase_code'] : '';
        $theme_name = isset($envato_purchase_code_verification['theme_name']) ? $envato_purchase_code_verification['theme_name'] : '';
        $url = REMOTE_API_URL;
        $post_data = array(
            'action' => 'verify_purchase_code',
            'item_purchase_code' => $item_purchase_code,
            'item_id' => THEME_ENVATO_ID,
            'theme_name' => $theme_name,
        );

        $response = wp_remote_post($url, array( 'body' => $post_data ));
        if ( ! is_wp_error($response) ) {
            $body = json_decode($response['body'], true);
            if ( 'true' == $body['success'] ) {
                $data_for_option = [
                    'last_verification_time' => time(),
                    'item_puchase_code' => $item_purchase_code,
                    'item_name' => $theme_name,
                    'item_id' => THEME_ENVATO_ID,
                    'urls' => json_encode($body['urls']),
                ];
                if ( $envato_purchase_code_verification ) {
                    update_option('item_purchase_code_verification', $data_for_option);
                }
            }
        }
    }

}

if ( ! function_exists('wp_dp_delete_default_content_callback') ) {
    add_action('wp_dp_delete_default_content', 'wp_dp_delete_default_content_callback');

    function wp_dp_delete_default_content_callback() {
        // Find and delete the WP default 'Sample Page'
        $defaultPage = get_page_by_title('Sample Page');
        if ( isset($defaultPage->ID) && $defaultPage->ID != '' ) {
            wp_delete_post($defaultPage->ID, true);
        }

        // Find and delete the WP default 'Hello world!' post
        $defaultPost = get_posts(array( 'title' => 'Hello World!' ));
        if ( isset($defaultPost[0]->ID) && $defaultPost[0]->ID != '' ) {
            wp_delete_post($defaultPost[0]->ID, true);
        }
    }

}