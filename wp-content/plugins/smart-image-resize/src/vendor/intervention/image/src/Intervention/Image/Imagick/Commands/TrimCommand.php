<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\Commands\AbstractCommand;
use Intervention\Image\Imagick\Color;
use WP_Smart_Image_Resize\Utilities\Env;

class TrimCommand extends AbstractCommand {
    /**
     * Trims away parts of an image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $base = $this->argument(0)->type('string')->value();
        $away = $this->argument(1)->value();
        $tolerance = $this->argument(2)->type('numeric')->value(0);
        $feather = $this->argument(3)->type('numeric')->value(0);

        $trimed = clone $image->getCore();

        $trimed->trimImage(65850 / 100 * $tolerance);
        $trimed->setImagePage(0, 0, 0, 0);

        if ($feather) {

            $color = maybe_hash_hex_color(strtolower(wp_sir_get_settings()['bg_color']));

            if (empty($color) && is_callable(array($trimed, 'getImageAlphaChannel')) &&  !$trimed->getImageAlphaChannel()) {
                $color = 'white';
            }

            $color = !empty($color) ? new \ImagickPixel($color) : new \ImagickPixel('none');

            $image_w = $trimed->getImageWidth();
            $image_h = $trimed->getImageHeight();
            $canvas_w = $image_w + $feather;
            $canvas_h = $image_h + $feather;
            $x = -max(0, ($canvas_w - $image_w) / 2);
            $y = -max(0, ($canvas_h - $image_h) / 2);

            $trimed->setImageBackgroundColor($color);
            $trimed->extentImage($canvas_w, $canvas_h, $x, $y);
            $trimed = $trimed->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
        }

        $image->setCore($trimed);

        return true;
    }
}
