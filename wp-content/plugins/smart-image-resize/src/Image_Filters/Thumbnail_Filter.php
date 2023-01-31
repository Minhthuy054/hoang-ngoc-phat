<?php

namespace WP_Smart_Image_Resize\Image_Filters;

use Intervention\Image\Filters\FilterInterface;
use \Intervention\Image\Image;
use WP_Smart_Image_Resize\Utilities\Env;

use function WP_Smart_Image_Resize\position_to_coords;

class Thumbnail_Filter implements FilterInterface {

    /**
     * The target thumbnail width/height.
     * @var array $size
     */
    protected $size_data;
    protected $size_name;
    protected $allow_upscale;


    public function __construct($size_name, $size_data) {
        $this->size_data           = $size_data;
        $this->size_name           = $size_name;

        /**
         * Filter whether to upscale small original images.
         * 
         * @param bool   $allow_upscale
         * @param string $size_name 
         * @param array   $size_data Array of {width, height}
         */
        $this->allow_upscale = (bool)apply_filters('wp_sir_maybe_upscale', true, $this->size_name, $this->size_data);
    }


    /**
     * Use Imagick to retain image profile instead of using composition
     */
    public function maybe_use_imagick($image) {

        try {
            if (!($image->getCore() instanceof \Imagick)) {
                throw new \Exception('Not an Imagick image');
            }

            // Get thumbnail position setting.
            $position = strtolower(apply_filters('wp_sir_canvas_position', 'center'));

            // Set the canvas color.
            $color = maybe_hash_hex_color(strtolower(wp_sir_get_settings()['bg_color']));

            // Backward compat with Imagick 6.x
            if (version_compare(Env::getImagickVersion(), '7', '<')) {
                if (empty($color) && is_callable(array($image->getCore(), 'getImageAlphaChannel')) && !$image->getCore()->getImageAlphaChannel()) {
                    $color = 'white';
                }
            }

            $color = !empty($color) ? new \ImagickPixel($color) : new \ImagickPixel('none');
            $image->getCore()->setImageBackgroundColor($color);

            // Get the original image width/height dimensions.
            $image_size = $image->getCore()->getImageGeometry();

            // Determine the output size depending on the `$alow_upscale` value.
            $size_width = $this->allow_upscale ? $this->size_data['width']  : min($image_size['width'], $this->size_data['width']);
            $size_height = $this->allow_upscale ? $this->size_data['height'] : min($image_size['height'], $this->size_data['height']);

            //  Resize the image to the given width/height dimensions.
            $image->getCore()->scaleImage($size_width, $size_height, true);

            // Get the new size after resize.
            $image_size = $image->getCore()->getImageGeometry();

            // Calculate position x and y axis.
            list($x, $y) = position_to_coords($position, $this->size_data, $image_size);

            // Resize canvas and place the image within the give position.
            $image->getCore()->extentImage($this->size_data['width'], $this->size_data['height'], $x, $y);

            // This is needed to use a custom background color.
            $image->getCore()->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);

            return $image;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function applyFilter(Image $image) {

        try {
            return $this->maybe_use_imagick($image);
        } catch (\Exception $e) {
        }

        // GD or Imagick failed.
        $image->resize($this->size_data['width'], $this->size_data['height'], function ($constraint) {

            /** @var $constraint Constraint */
            // Preserve the original aspect-ratio of the given image.
            $constraint->aspectRatio();

            if (!$this->allow_upscale) {
                $constraint->upsize();
            }
        });

        return $image->filter(new Recanvas_Filter($this->size_data));
    }
}
