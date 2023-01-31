<?php

namespace WP_Smart_Image_Resize\Image_Filters;

use EasyWatermark\Watermark\Watermark;
use \Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;
use WP_Smart_Image_Resize\Image_Manager;
use WP_Smart_Image_Resize\Image_Meta;

class Watermark_Filter implements FilterInterface
{   

    private function is_position_supported($position){
        $supported_positions = ['top-left', 'top-right', 'bottom-left', 'bottom-right', 'center'];
        return in_array($position, $supported_positions);
    }

    /**
     * @param Image $image
     *
     * @return Image
     */
    public function applyFilter(Image $image)
    {
        $settings = wp_sir_get_settings();

        if (!$settings['enable_watermark']) {
            return $image;
        }

        $watermark_image = $settings['watermark_image'];

        if(empty($watermark_image)){
            return $image;
        }

        $watermark_fullpath = get_attached_file($watermark_image);

        if(!is_readable($watermark_fullpath)){
            return $image;
        }

        $size_percent = (int)$settings['watermark_size'];

        $size_percent = $size_percent ?: 50;

        $position = $settings['watermark_position'];

        if(!$this->is_position_supported($position)){
            return $image;
        }

        try {
            
            $m = new Image_Manager();
            $wm = $m->make($watermark_fullpath);

            if ($wm->getWidth() >= $wm->getHeight()) {
                $watermark_w = $image->getWidth() * $size_percent / 100;
                if($watermark_w >= $image->getWidth()){
                    $watermark_w = $image->getWidth();
                }
                $watermark_h = $wm->getHeight() * $watermark_w  / $wm->getWidth();

            }else{
             
                $watermark_h = $image->getHeight() * $size_percent / 100;
                if($watermark_h >= $image->getHeight()){
                    $watermark_h = $image->getHeight();
                }
                $watermark_w = $wm->getWidth() * $watermark_h  / $wm->getHeight();
            }

            if($watermark_w >= $image->getWidth()){
                $watermark_w = $image->getWidth();
                $watermark_h = $wm->getHeight() * $watermark_w  / $wm->getWidth();
            }
            if($watermark_h >= $image->getHeight()){
                $watermark_h = $image->getHeight();
                $watermark_w = $wm->getWidth() * $watermark_h  / $wm->getHeight();
            }

            
            $wm->resize($watermark_w, $watermark_h);
            $wm->opacity((int)$settings['watermark_opacity']);

            $offset_x = (int)$settings['watermark_offset']['x'];
            $offset_y = (int)$settings['watermark_offset']['y'];

            if($position === 'center'){
                $offset_x = $offset_y = 0;
            }
            $image->insert($wm, $position, $offset_x, $offset_y);
        } catch (\Exception $e) {
        }
        return $image;
    }
}
