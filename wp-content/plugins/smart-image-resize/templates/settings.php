<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://nabillemsieh.com
 * @since      1.0.0
 *
 * @package    WP_Smart_Image_Resize
 * @subpackage WP_Smart_Image_Resize/templates
 */

$current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
?>
<div class="wrap">
    
    <h1>Smart Image Resize for WooCommerce</h1>
    
    <h2 class="nav-tab-wrapper">
        <a href="?page=wp-smart-image-resize&tab=general"
           class="nav-tab <?php echo $current_tab === 'general' ? 'nav-tab-active' : '' ?>">Settings</a>
        <a href="?page=wp-smart-image-resize&tab=regenerate_thumbnails"
           class="nav-tab <?php echo $current_tab === 'regenerate_thumbnails' ? 'nav-tab-active' : '' ?>">Regenerate
            Thumbnails</a>
        
    </h2>

    <?php if ( $current_tab === 'general' ): ?>

        <div class="wpsirSettingsContainer">
            <div>
                <form method="post" action="options.php">
                    <?php
                    settings_fields( WP_SIR_NAME );
                    do_settings_sections( WP_SIR_NAME );
                    submit_button();
                    ?>
                </form>
            </div>
            <div>
                <div class="wpsirInfoBox">
                    <h3>Resources</h3>
                    <ul>
                        <li><a target="_blank" href="https://sirplugin.com"><i aria-hidden="true"
                                                                               class="dashicons dashicons-external"></i>
                                Website</a></li>
                        <li><a target="_blank" href="https://sirplugin.com/guide.html"><i aria-hidden="true"
                                                                                          class="dashicons dashicons-external"></i>
                                Documentation</a></li>
                        <li><a target="_blank" href="https://sirplugin.com/contact.html"><i aria-hidden="true"
                                                                                            class="dashicons dashicons-external"></i>
                                Support</a></li>

                        
                        <li><a target="_blank" href="https://sirplugin.com#pro"><i aria-hidden="true"
                                                                                   class="dashicons dashicons-external"></i>
                                Upgrade to PRO</a></li>
                        
                    </ul>
                </div>
            </div>
        </div>

    <?php endif;
    if ( $current_tab === 'regenerate_thumbnails' ):
        ?>
        <div class="wp-sir-regenerate-thumbnails" style="padding:10px">
            <p style="margin-bottom:5px">Follow these steps to resize images already uploaded to match your settings.</p>
            <ol>
                <?php if ( !wp_sir_regen_thumb_active() ): ?>
                    <li>Install <a
                                href="<?php echo admin_url( 'plugin-install.php?s=Regenerate+Thumbnails&tab=search&type=term' ) ?>">Regenerate
                            Thumbnails plugin</a>.
                    </li>
                <?php endif; ?>
                <li>Navigate to
                    <?php if ( wp_sir_regen_thumb_active() ): ?>
                        <a href="<?php echo admin_url() ?>tools.php?page=regenerate-thumbnails">Tools → Regenerate
                            Thumbnails</a>
                    <?php else: ?>
                        Tools > Regenerate Thumbnails.
                    <?php endif; ?>
                </li>
                <li>Click the <b>Regenerate Thumbnails for All Attachments</b> button to start resizing</li>
            </ol>
            <p>
                <b>NOTE:</b> Make sure you purge cache if old images still showing up, including your browser, caching
                plugin, and Cloudflare.
            </p>
          
        </div>
    <?php endif; ?>
    

</div>


