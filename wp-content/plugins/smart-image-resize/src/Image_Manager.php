<?php

namespace WP_Smart_Image_Resize;

use Intervention\Image\ImageManager;
use WP_Smart_Image_Resize\Utilities\Env;

class Image_Manager extends ImageManager {
    /**
     * @param $size
     */
    public function __construct($driver = false) {

        if (!$driver) {
            $driver = Env::active_image_processor();
        }
        parent::__construct(compact('driver'));
    }

    public function make($data) {
        wp_raise_memory_limit('image');

        return parent::make($data);
    }
}
