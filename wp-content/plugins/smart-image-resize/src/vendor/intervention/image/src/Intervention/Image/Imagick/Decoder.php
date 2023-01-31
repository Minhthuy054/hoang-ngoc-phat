<?php

namespace Intervention\Image\Imagick;

use Intervention\Image\AbstractDecoder;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Image;

class Decoder extends AbstractDecoder {
    /**
     * Initiates new image from path in filesystem.
     *
     * @param  string $path
     * @return \Intervention\Image\Image
     */
    public function initFromPath($path) {
        $core = new \Imagick;

        try {
            $core->setBackgroundColor(new \ImagickPixel('transparent'));
            $core->readImage($path);
        } catch (\Exception $e) {
            throw new \Exception("Unable to read image from path ({$path}). " . $e->getMessage(), $e->getCode(), $e);
        }

        try {

            if ($core->getImageColorspace() === \Imagick::COLORSPACE_CMYK) {

                try {
                    $has_icc_profile = (bool) $core->getImageProfile('icc');
                } catch (\ImagickException $e) {
                    $has_icc_profile = false;
                }

                if (!$has_icc_profile) {
                    $core->profileImage('icc', file_get_contents(__DIR__ . '/USWebUncoated.icc'));
                }

                $core->profileImage('icc', file_get_contents(__DIR__ . '/sRGB_IEC61966-2-1_black_scaled.icc'));

                $core->setColorspace(\Imagick::COLORSPACE_RGB);
            }
        
            $type = defined('\Imagick::IMGTYPE_TRUECOLORALPHA') ? \Imagick::IMGTYPE_TRUECOLORALPHA : (defined('\Imagick::IMGTYPE_TRUECOLORMATTE') ? \Imagick::IMGTYPE_TRUECOLORMATTE : 7);
            $core->setType($type);

        } catch (\Exception $e) {
           $core->transformImageColorspace(\Imagick::COLORSPACE_SRGB);
        }

        $image = $this->initFromImagick($core);
        $image->setFileInfoFromPath($path);

        return $image;
    }

    /**
     * Initiates new image from GD resource.
     *
     * @param  resource $resource
     * @return \Intervention\Image\Image
     */
    public function initFromGdResource($resource) {
        throw new NotSupportedException(
            'Imagick driver is unable to init from GD resource.'
        );
    }

    /**
     * Initiates new image from Imagick object.
     *
     * @param  Imagick $object
     * @return \Intervention\Image\Image
     */
    public function initFromImagick(\Imagick $object) {
        // currently animations are not supported
        // so all images are turned into static
        $object = $this->removeAnimation($object);

        // reset image orientation
        $object->setImageOrientation(\Imagick::ORIENTATION_UNDEFINED);

        return new Image(new Driver, $object);
    }

    /**
     * Initiates new image from binary data.
     *
     * @param  string $data
     * @return \Intervention\Image\Image
     */
    public function initFromBinary($binary) {
        $core = new \Imagick;

        try {
            $core->setBackgroundColor(new \ImagickPixel('transparent'));

            $core->readImageBlob($binary);
        } catch (\ImagickException $e) {
            throw new NotReadableException(
                'Unable to read image from binary data.',
                0,
                $e
            );
        }

        // build image
        $image = $this->initFromImagick($core);
        $image->mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);

        return $image;
    }

    /**
     * Turns object into one frame Imagick object
     * by removing all frames except first.
     *
     * @param  Imagick $object
     * @return Imagick
     */
    private function removeAnimation(\Imagick $object) {
        $imagick = new \Imagick;

        foreach ($object as $frame) {
            $imagick->addImage($frame->getImage());
            break;
        }

        $object->destroy();

        return $imagick;
    }
}
