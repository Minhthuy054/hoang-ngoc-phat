<?php

namespace WP_Smart_Image_Resize\Image_Filters;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;
use WP_Smart_Image_Resize\Image_Manager;

class Recanvas_Filter implements FilterInterface
{

    /**
     * Default image position.
     *
     * @var string DEFAULT_POSITION
     */
    protected $defaultPosition = 'center';

    /**
     * Available image positions.
     *
     * @var array POSITIONS
     */
    protected $supportedPositions = [
        'center',
        'top',
        'bottom',
        'left',
        'right'
    ];


    /**
     * Canvas width/height.
     *
     * @var array $size {width, height}
     */
    protected $size;


    public function __construct($size)
    {
        $this->size    = $size;
    }


    public function getCanvasColor()
    {
        return maybe_hash_hex_color(wp_sir_get_settings()['bg_color']) ?: null;
    }

    public function getImagePosition()
    {
        $position = strtolower(apply_filters('wp_sir_canvas_position', $this->defaultPosition));

        if (!in_array($position, $this->supportedPositions)) {
            $position = $this->defaultPosition;
        }

        return $position;
    }

    public function applyFilter(Image $image)
    {

        // Place the image inside a canvas.
        $image_manager = new Image_Manager();
        $canvas = $image_manager->canvas($this->size['width'], $this->size['height'], $this->getCanvasColor(), $image);

        return $canvas->insert($image, $this->getImagePosition());
    }
}
