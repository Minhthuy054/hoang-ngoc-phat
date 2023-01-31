<?php

namespace WP_Smart_Image_Resize;

class Process_Media_Library_Upload
{
    use Singleton_Trait;

    const COOKIE_NAME = 'wp_sir_process_ml_upload';

    function initialize(){
        add_action( 'pre-html-upload-ui', [ $this, 'render_form' ] );
        add_action( 'pre-plupload-upload-ui', [ $this, 'render_form' ] );
        add_action('begin_wcfm_products_manage_form', function(){
         ?>
        <script>
            window._is_wcfm_product_form = true;
        </script>
         <?php
        });
       
        add_action('wp_print_footer_scripts', function(){
           ?>
    <script>
       (function($){

        if(! window._is_wcfm_product_form){
                return;
        }

        var __uploaderOpen = null;

        function toggleProcessableState(ev){
            __uploadedOpen = setInterval(function(){
                if(wp.media.frame){
                    clearInterval(__uploaderOpen);
                    wp.media.frame.uploader.uploader.param('_processable_image', true);
                    wp.media.frame.on('close escape', function(){
                        wp.media.frame.uploader.uploader.param('_processable_image', false);
                    });
            }
            }, 100);
        }

            $('.wcfm-wp-fields-uploader').each(function(){
                $(this).find('img').on('click', toggleProcessableState);
            });

            $('#gallery_img').on('click', '.add_multi_input_block',function(){
                
                $('.wcfm-wp-fields-uploader').each(function(){
                    $(this).find('img').off('click', toggleProcessableState);
               });
               
               $('.wcfm-wp-fields-uploader').each(function(){
                     $(this).find('img').on('click', toggleProcessableState);
                });
            });
           
               
      
       })(jQuery);
    </script>
            <?php
        });
       
    }
    static function is_media_screen()
    {

        require_once(ABSPATH . 'wp-admin/includes/screen.php');

        if( ! function_exists('get_current_screen') ){
            return false;
        }

        $screen = get_current_screen();

        return $screen
            && is_object( $screen )
            && in_array( $screen->id , ['media', 'upload']);
    }

    function render_form()
    {
        if ( ! static::is_media_screen() ) {
            return;
        }

        $isOn = !empty( $_COOKIE[self::COOKIE_NAME] ) ? filter_var($_COOKIE[self::COOKIE_NAME], FILTER_VALIDATE_BOOLEAN) : true;
        $isOn = apply_filters('wp_sir_process_media_library_upload_by_default', $isOn );
        ?>
        <div class="wpsirProcessMediaLibraryImageWraper">
            <h3 class="wpsirProcessMediaLibraryImageTitle">Smart Image Resize:</h3>
            <label for="processMediaLibraryImage"><input
                        id="processMediaLibraryImage"
                        type="checkbox"
                        <?php checked($isOn, true, true) ?>
                        class="wp-sir-as-toggle">
                <span ><?php esc_html_e( 'When uploading an image, resize it to match your settings.',
                        'wp-smart-image-resize' ); ?></span></label>
        </div>
        <?php
    }
}

Process_Media_Library_Upload::instance()->initialize();
