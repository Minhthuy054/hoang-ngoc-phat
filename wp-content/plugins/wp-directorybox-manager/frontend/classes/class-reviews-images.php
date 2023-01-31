<?php
/**
 * File Type: Reviews Image Upload Option
 */
if ( ! class_exists('Homevillas_Reviews_Images') ) {

    class Homevillas_Reviews_Images {

        /**
         * Start construct Functions
         */
        public function __construct() {

            add_action('homevillas_reviews_image_field', array( $this, 'homevillas_reviews_image_field_callback' ), 10);
        }

        /*
         * Upload field on review form
         */

        public function homevillas_reviews_image_field_callback($post_id) {
            global $wp_dp_plugin_options;
            ?>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 reviews-images-holder">
                <form name="review-images" id="review-images" class="review-images">
                    <a href="javascript:;" class="review-images-btn"><?php echo wp_dp_plugin_text_srt('wp_dp_reviews_browse'); ?></a><span class="review-image-text"><?php echo wp_dp_plugin_text_srt('wp_dp_select_images'); ?></span>
                    <input type="file" multiple name="review_images[]" id="review-images" class="hidden review-images"> <span class="reset-all-review-images hidden"><a href="javascript:;"><?php echo wp_dp_plugin_text_srt('wp_dp_reset_all'); ?></a></span>
                </form>
            </div>
            <?php
        }

    }

    global $homevillas_reviews_images;
    $homevillas_reviews_images = new Homevillas_Reviews_Images();
}