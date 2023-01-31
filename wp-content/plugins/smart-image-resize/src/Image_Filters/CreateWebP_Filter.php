<?php

namespace WP_Smart_Image_Resize\Image_Filters;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;
use Exception;
use Intervention\Image\Exception\NotSupportedException;
use WP_Smart_Image_Resize\Image_Manager;
use WP_Smart_Image_Resize\Utilities\Env;

use function WP_Smart_Image_Resize\is_imagick_image;

class CreateWebP_Filter implements FilterInterface {

    /**
     * The output image full path.
     *
     * @var string $path
     */
    protected $path;
    protected $metaobject;
    protected $size;

    public function __construct($path, $size, $metaobject = false) {
        $this->path = $path;
        $this->size = $size;
        $this->metaobject = $metaobject;
    }

    public function applyFilter(Image $image) {

        if (!wp_sir_get_settings()['enable_webp']) {
            return $image;
        }

        // Already a WebP image, no need to create a new one.
        if (strpos($image->mime, 'image/webp') !== false) {
            return $image;
        }

        try {
            @unlink($this->path);
            $webp_image = clone $image;
            $webp_image->save($this->path);
            $webp_image->destroy();
        } catch (NotSupportedException $e) {
            try {
                $fallbackdriver  = Env::get_webp_fallback_image_processor($image->getCore());
                $fallbackmanager = new Image_Manager($fallbackdriver);
                $fallbackimage =  $fallbackmanager->make($image->basePath());

                if ($this->size === 'full' && is_object($this->metaobject)) {
                    if (is_imagick_image($fallbackimage->getCore())) {
                        $fallbackimage = $fallbackimage->filter(new Trim_Filter($this->metaobject));
                    } 
                    $this->metaobject->setMetaItem('_trimmed_width', $fallbackimage->getWidth());
                    $this->metaobject->setMetaItem('_trimmed_height', $fallbackimage->getHeight());
                   
                }

                $fallbackimage->save($this->path)->destroy();
            } catch (Exception $e) {
            }
        } catch (Exception $e) {
        }

        return $image;
    }
}
