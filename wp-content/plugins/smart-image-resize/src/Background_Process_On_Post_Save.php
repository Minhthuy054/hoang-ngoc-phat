<?php

namespace WP_Smart_Image_Resize;

use WP_Smart_Image_Resize\Singleton_Trait;

final class Background_Process_On_Post_Save {

    use Singleton_Trait;
    
    const JOB_HOOK = 'wp_sir_process_image';
    const JOB_GROUP = 'wp_sir_process_on_post_save';
    
    public function init() {
        add_action('save_post', [$this, 'maybe_queue_image'], 10, 2);
        add_action('wp_sir_process_image', [$this, 'process_image']);
        add_action('woocommerce_save_product_variation', [$this, 'process_product_variation_image']);
    }

    public function process_product_variation_image($product_id){
        
        if(! $this->_is_background_processing_allowed() ){
            return;
        }

        $process_post_types = \wp_sir_get_processable_post_types();

        if( ! in_array(\get_post_type($product_id), $process_post_types) ){
            return;
        }

        $this->_push_to_queue(\get_post_thumbnail_id($product_id));
    }

    /**
     * Run thumbnails regeneration for the given image.
     */
    public function process_image($image_id) {

        if(! $this->_is_background_processing_allowed() ){
            return;
        }

        $file_path = \get_attached_file($image_id);

        if(! is_readable($file_path) ){
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        
        $meta = \wp_generate_attachment_metadata($image_id, $file_path);

        if(!empty($meta) && is_array($meta) && isset($meta['_processed_at'])){
            \wp_update_attachment_metadata($image_id, $meta);
        }
    }

    private function _is_background_processing_allowed(){
        return apply_filters('wp_sir_allow_background_processing', true);
    }
    private function _is_as_available(){
        return function_exists('\as_has_scheduled_action') 
            && function_exists('\as_unschedule_action') 
            && function_exists('\as_enqueue_async_action');

    }
    public function maybe_queue_image($post_id, $post) {

        if(! $this->_is_background_processing_allowed() ){
            return;
        }

        if(! $this->_is_as_available() ){
            return;
        }
     
        $process_post_types = \wp_sir_get_processable_post_types();

        if (!in_array($post->post_type, $process_post_types, true)) {
            return;
        }

        $image_ids = [];

        if ($main_image_id = \get_post_thumbnail_id($post)) {
            $image_ids[] = $main_image_id;
        }

        if ($post->post_type === 'product' && function_exists('\wc_get_product')) {
            $product = \wc_get_product($post_id);
            
            $variation_ids = $product->get_children();
            if(!empty($variation_ids) ){
                foreach ($variation_ids as $variation_id) {
                    $image_ids[] = \get_post_thumbnail_id($variation_id);
                }
            }

            if (method_exists($product, 'get_gallery_image_ids')) {
                $image_ids = array_merge($image_ids, $product->get_gallery_image_ids());
            }elseif(method_exists($product, 'get_gallery_attachment_ids')){
                $image_ids = array_merge($image_ids, $product->get_gallery_attachment_ids());
            }
        }

        $this->_push_to_queue($image_ids);
        
    }

    private function _push_to_queue($image_ids){
        $image_ids = (array)$image_ids;
        
        $image_ids = array_unique(array_filter($image_ids));

        if(empty($image_ids) ){
            return;
        }

        foreach ($image_ids as $image_id) {

            // If the image is already processed, skip it.
            if (\get_post_meta($image_id, '_processed_at', true)) {
                continue;
            }

            $job_args = compact('image_id');

            $is_queued = \as_has_scheduled_action(self::JOB_HOOK, $job_args, self::JOB_GROUP);
            if ($is_queued) {
                \as_unschedule_action(self::JOB_HOOK, $job_args, self::JOB_GROUP);
            }
            \as_enqueue_async_action(self::JOB_HOOK, $job_args, self::JOB_GROUP);
        }

          
        }
}

Background_Process_On_Post_Save::instance()->init();
