<?php
/**
 * File Type: Listing Sidebar Map Page Element
 */
if ( ! class_exists('wp_dp_attachments_element') ) {

    class wp_dp_attachments_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_attachments_html', array( $this, 'wp_dp_attachments_html_callback' ), 11, 1);
        }

        public function wp_dp_attachments_html_callback($listing_id = '') {
            global $post, $wp_dp_plugin_options;
            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }

            $wp_dp_listing_type_slug = get_post_meta($listing_id, 'wp_dp_listing_type', true);

            $listing_type_id = 0;
            if ( $post = get_page_by_path($wp_dp_listing_type_slug, OBJECT, 'listing-type') ) {
                $listing_type_id = $post->ID;
            }
            $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
            $wp_dp_full_data = get_post_meta($listing_type_id, 'wp_dp_full_data', true);
            if ( ! isset($wp_dp_full_data['wp_dp_attachments_options_element']) || $wp_dp_full_data['wp_dp_attachments_options_element'] != 'on' ) {
                return false;
            }

            $wp_dp_transaction_listing_doc_num = get_post_meta($listing_id, 'wp_dp_transaction_listing_doc_num', true);
            $wp_dp_attachments = get_post_meta($listing_id, 'wp_dp_attachments', true);
            $counter = 1;
            $element_title = get_post_meta($listing_type_id, 'wp_dp_listing_type_title_file_attachment', true);
            if ( isset($wp_dp_attachments) && ! empty($wp_dp_attachments) ) {
                ?>
                <div id="attachments" class="attachment-holder">
                    <div class="element-title">
                        <h3><?php echo esc_html($element_title); ?></h3>
                    </div>
                    <ul class="row">
                        <?php
                        foreach ( $wp_dp_attachments as $key => $attchment ) {
                            if ( $counter <= $wp_dp_transaction_listing_doc_num ) {
                                if ( isset($attchment) && count($attchment) > 0 ) {
                                    extract($attchment);
                                }
                                if ( $attachment_file != '' ) {
                                    $file_url = wp_get_attachment_url($attachment_file);
                                    $filet_type = wp_check_filetype($file_url);
                                    $filet_type = isset($filet_type['ext']) ? $filet_type['ext'] : '';
                                    $file_size = $this->getSize(get_attached_file($attachment_file));
                                    $thumb_url = wp_dp::plugin_url() . '/assets/common/attachment-images/attach-' . $filet_type . '.png';
                                    ?>
                                    <li class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="img-holder">
                                            <figure><a href="<?php echo esc_url($file_url); ?>" download="<?php echo esc_html($attachment_title); ?>"><img src="<?php echo esc_url($thumb_url); ?>" alt=""></a></figure>
                                        </div>
                                        <div class="text-holder">
                                            <?php if ( $attachment_title != '' ) { ?>
                                                <strong><a href="<?php echo esc_url($file_url); ?>" download="<?php echo esc_html($attachment_title); ?>"><?php echo esc_html($attachment_title); ?></a></strong>
                                            <?php } ?>
                                            <ul class="attachment-formats">
                                                <li><a href="<?php echo esc_url($file_url); ?>" download="<?php echo esc_html($attachment_title); ?>"><?php echo wp_dp_plugin_text_srt('wp_dp_attachments_downloads'); ?></a></li>
                                            </ul>
                                        </div>
                                    </li>
                                <?php } ?>
                                <?php
                            } $counter ++;
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
        }

        public function getSize($file) {
            $bytes = filesize($file);
            $s = array( 'b', 'Kb', 'Mb', 'Gb' );
            $e = floor(log($bytes) / log(1024));

            return sprintf('%.2f ' . $s[$e], ($bytes / pow(1024, floor($e))));
        }

    }

    global $wp_dp_attachments;
    $wp_dp_attachments = new wp_dp_attachments_element();
}