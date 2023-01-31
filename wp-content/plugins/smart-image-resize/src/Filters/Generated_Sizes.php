<?php

namespace WP_Smart_Image_Resize\Filters;

use WP_Smart_Image_Resize\Exceptions\Invalid_Image_Meta_Exception;
use WP_Smart_Image_Resize\Image_Meta;
use WP_Smart_Image_Resize\Processable_Trait;

class Generated_Sizes extends Base_Filter
{
    use Processable_Trait;

    public function listen()
    {
        add_filter('intermediate_image_sizes_advanced', [$this, 'removeUnwantedSizes'], 999, 3);
    }

    /**
     * Set generatable sizes.
     * This will free-up disk space from unused sizes.
     *
     * @param array $sizes
     * @param array $metadata
     * @param null $image_id
     *
     * @return array
     */
    public function removeUnwantedSizes($sizes, $metadata, $image_id = null)
    {
        // We need the image ID to determine whether the uploaded
        // image is part of the processable images.
        if (! $image_id) {
            return $sizes;
        }

        try {
            $image_meta = new Image_Meta($image_id, $metadata);

            $isProcessable = $this->isProcessable($image_id, $image_meta);

            // Since this filter is applied multiple times
            // we need to cache result to improve performances when it's used later.
            wp_cache_add('processable_image_'.$image_id, ($isProcessable ? 'yes' : 'no'), 'wp_sir_cache');
            
            $filtered = [];

            if ($isProcessable) {
                $settings = wp_sir_get_settings();
                $excluded = _wp_sir_get_excluded_sizes();
                foreach($sizes as $size_name => $size) {
                    if (in_array($size_name, $settings['sizes']) && in_array($size_name, $excluded)) {
                        $filtered[$size_name] = $size;
                    }
                }
                return $filtered;
            }
        } catch (Invalid_Image_Meta_Exception $e) {
        }

        return $sizes;
    }
}
