<?php

namespace WP_Smart_Image_Resize;

use Exception;
use WP_Smart_Image_Resize\Exceptions\Invalid_Image_Meta_Exception;
use WP_Smart_Image_Resize\Image_Filters\Thumbnail_Filter;
use WP_Smart_Image_Resize\Image_Filters\Trim_Filter;
use WP_Smart_Image_Resize\Image_Filters\CreateWebP_Filter;
use WP_Smart_Image_Resize\Image_Filters\Watermark_Filter;
use WP_Smart_Image_Resize\Utilities\File;

/*
 * Class WP_Smart_Image_Resize\Image_Editor
 *
 * @package WP_Smart_Image_Resize\Inc
 * 
 */

defined('ABSPATH') || exit;

if (!class_exists('\WP_Smart_Image_Resize\Image_Editor')) :

    final class Image_Editor {
        use Processable_Trait;
        use Runtime_Config_Trait;

        /**
         * @var \WP_Smart_Image_Resize\Image_Editor
         */
        protected static $instance = null;


        /**
         * @return \WP_Smart_Image_Resize\Image_Editor
         */
        public static function get_instance() {
            if (is_null(static::$instance)) {
                static::$instance = new self;
            }

            return static::$instance;
        }

        /**
         * Register hooks.
         */
        public function run() {

            // A low priority < 10 to let plugins optimize thumbnails.
            add_filter('wp_generate_attachment_metadata', [$this, 'processImage'],9, 2);

            // Force 1:1 size for single product thumbnail.
            // @see  force_square_woocommerce_single()
            add_filter('woocommerce_get_image_size_single', [$this, 'forceSquareWooCommerceSingle']);

            // Force woocommerce single on single product page.
            // @see force_woocommerce_single()
            add_filter('woocommerce_gallery_image_size', [$this, 'forceWooCommerceSingle'], PHP_INT_MAX);

        }

        /**
         * Determine whether the given size is selected.
         *
         * @param array $sizes
         *
         * @return bool
         */
        public function isProcessableSize($sizes) {
            if (!is_array($sizes)) {
                $sizes = (array)$sizes;
            }

            $selected_sizes = apply_filters('wp_sir_sizes', wp_sir_get_settings()['sizes']);

            return count(array_intersect($sizes, $selected_sizes)) === count($sizes);
        }

        /**
         * Use 1:1 for single size when selected.
         *
         * @param string|array
         *
         * @return array
         */


        public function forceSquareWooCommerceSingle($size) {
            if (!$this->isProcessableSize(['woocommerce_single', 'shop_single'])) {
                return $size;
            }

            // If height is not set, make it square.
            if ($size['width'] && !$size['height']) {
                $size['height'] = $size['width'];
            }

            return $size;
        }

        /**
         * Force woocommerce_single size on single product page.
         *
         * @hook woocommerce_gallery_image_size
         *
         * @param string $size
         *
         * @return string
         */
        public function forceWooCommerceSingle($size) {
            if (!apply_filters('wp_sir_force_woocommerce_single', true)) {
                return $size;
            }

            if ($this->isProcessableSize(['woocommerce_single', 'shop_single'])) {
                return 'woocommerce_single';
            }

            return $size;
        }

        /**
         * Proceed image editing and thumbnails generation.
         *
         * @param array $metadata
         * @param int $imageId
         *
         * @return array
         */

        public function processImage($metadata, $imageId) {


            try {

                // Get global settings.
                $settings = wp_sir_get_settings();

                // Bail if resizing is disabled.
                if (!$settings['enable']) {
                    return $metadata;
                }

                
                if (Quota::isExceeded()) {
                    return $metadata;
                }
                

                // Wrap the current image metadata.
                // This allows manupilating metadata easier.
                // TODO: Remove in v2.0.
                $imageMeta = new Image_Meta($imageId, $metadata);

                // Check whether the current image is processable.
                // By default, the plugin does only process specified images.
                if (!$this->isProcessable($imageId, $imageMeta)) {
                    return $metadata;
                }

                // TODO: Use WP_Image_Editor class instead.
                $imageManager = new Image_Manager();

                // Let's try to load the given image to memory,
                $image = $imageManager->make($imageMeta->getOriginalFullPath());

                @set_time_limit(0);

                $imageMeta->setMimeType($image->mime());

                $image->filter(new Trim_Filter($imageMeta));

                $imageMeta->setBackup();

                $imageMeta->clearSizes();
                $sameSizes = [];

                $excluded_sizes = [];

                $processed_at  = time();

                $excluded_size_names = _wp_sir_get_excluded_sizes();
                $sizes = _wp_sir_get_sizes_to_generate();
                foreach ($sizes as $sizeName => $sizeData) {

                    @set_time_limit(0);

                    if (in_array($sizeName, $excluded_size_names)) {
                        if (!empty($metadata['sizes'][$sizeName])) {
                            $excluded_sizes[$sizeName] = $metadata['sizes'][$sizeName];
                            $excluded_sizes[$sizeName]['_processed_by'] = WP_SIR_VERSION;
                            $excluded_sizes[$sizeName]['_processed_at'] = $processed_at;
                        }

                        continue;
                    }
                    // Ignore duplicated sizes.
                    $sizeHash = $sizeData['width'] . '|' . $sizeData['height'];

                    if (isset($sameSizes[$sizeHash])) {
                        $imageMeta->setSizeData($sizeName, $imageMeta->getSizeData($sameSizes[$sizeHash]));
                        continue;
                    }

                    $thumb_object = clone $image;
                    $thumb_object = $thumb_object->filter(new Thumbnail_Filter($sizeName, $sizeData));
                    

                    $thumb_path = $this->generateThumbPath($image->basePath(), $sizeData, $sizeName, $imageId);

                    @unlink($thumb_path);

                    $quality = 100 - (int)$settings['jpg_quality'];
                    
                    $thumb_object->save($thumb_path, $quality);

                    $imageMeta->setSizeData($sizeName, [
                        'width'     => $thumb_object->getWidth(),
                        'height'    => $thumb_object->getHeight(),
                        'file'      => $thumb_object->basename,
                        'mime-type' => $thumb_object->mime(),
                        '_processed_at' => $processed_at,
                        '_processed_by' => WP_SIR_VERSION,
                    ]);

                    $sameSizes[$sizeHash] = $sizeName;


                    $thumb_object->destroy();
                }

                $image->destroy();

                $imageMeta->markSizesRegenerated($processed_at);

                $new_meta = $imageMeta->toArray();

                $new_meta['sizes'] = array_merge($excluded_sizes, $new_meta['sizes']);

                $this->deleteOrphanThumbnails($imageId, $metadata, $new_meta);
                
                
                Quota::consume($imageId);
                

                return $new_meta;
            } catch (Invalid_Image_Meta_Exception $e) {
                return $metadata;
            } catch (Exception $e) {

                if (defined('WP_CLI') && WP_CLI) {
                    $msg = "Smart Image Resize: " . $e->getMessage();

                    if (!empty($metadata['file'])) {
                        $msg .= " (Path: " . $metadata['file'] . ", ID: " . $imageId . ")";
                    }

                    \WP_CLI::warning($msg);
                } else {
                    wp_send_json_error([
                        'message' => "Smart Image Resize: " . $e->getMessage()
                    ]);
                }

                return $metadata;
            }
        }

        private function deleteOrphanThumbnails($imageId, $oldMeta, $newMeta) {
            // Old file names to delete.
            $oldFileNames = [];
            // Since we prevent WP from generating any additional size via 
            // the filter `intermediate_image_sizes_advanced`, when a third-party triggers
            // the plugin the `$oldMeta[sizes]` won't contains unselected sizes
            // to manage this, we temporary store previously generated sizes
            //  in the `_old_image_meta` via the filter `wp_update_attachment_metadata`
            // to retreive them later here when running a clean up.

            $orphanMeta = get_post_meta($imageId, '_old_image_meta', true);
            if (is_array($orphanMeta) && !empty($orphanMeta) && !empty($orphanMeta['sizes'])) {
                foreach ($orphanMeta['sizes'] as $orphanSize) {
                    $oldFileNames[] = $orphanSize['file'];
                }
            }

            if (!empty($oldMeta['sizes'])) {
                foreach ($oldMeta['sizes'] as $oldSize) {
                    $oldFileNames[] = $oldSize['file'];
                }
            }

            $oldFileNames = array_unique($oldFileNames);

            if (empty($oldFileNames)) {
                return;
            }

            $newFileNames = array_map(function ($size) {
                return $size['file'];
            }, $newMeta['sizes']);

            $uploadsPath  = wp_get_upload_dir()['basedir'];
            $imageDirPath = trailingslashit($uploadsPath) . trailingslashit(dirname($oldMeta['file']));


            foreach ($oldFileNames as $file) {

                // Clean up WebP size if the "WebP Images" feature is disabled.
                if (!wp_sir_get_settings()['enable_webp']) {

                    $webp_file = File::mb_pathinfo($file, PATHINFO_FILENAME) . '.webp';

                    // Check if the WebP file is not the same as the size or original file.
                    if ($webp_file !== wp_basename($file) && $webp_file !== wp_basename($oldMeta['file'])) {
                        @unlink($imageDirPath . $webp_file);
                    }
                }

                // Prevent accidently deleting original image.
                if ($file === wp_basename($oldMeta['file'])) {
                    continue;
                }

                if (!in_array($file, $newFileNames)) {

                    // Delete old thumbnails, including JPG-converted images as well.
                    @unlink($imageDirPath . $file);

                    // Delete old WebP images if present.
                    $webp = $imageDirPath . File::mb_pathinfo($file, PATHINFO_FILENAME) . '.webp';
                    @unlink($webp);
                }
            }

            // Clean up full size WebP file if "WebP images" feature has been disabled.
            if (!wp_sir_get_settings()['enable_webp']) {
                $webp_file = File::mb_pathinfo($oldMeta['file'], PATHINFO_FILENAME) . '.webp';

                // Only delete if original file is not the same as the WebP file
                // to prevent accidently deleting the original file.
                if ($webp_file !== wp_basename($oldMeta['file'])) {
                    @unlink($imageDirPath . $webp_file);
                }
            }
        }

        /**
         * Return sizes to resize.
         *
         * @return array
         */


        private function getSizesToGenerate() {
            $sizeNames = apply_filters('wp_sir_sizes', wp_sir_get_settings()['sizes']);

            $sizes = [];

            foreach ($sizeNames as $sizeName) {

                // TODO: Use `wp_sir_get_additional_sizes` directly.
                $size = wp_sir_get_size_dimensions($sizeName);

                if (!empty($size) && !empty($size['width']) && !empty($size['height'])) {
                    $sizes[$sizeName] = $size;
                }
            }

            if (!apply_filters('wp_sir_enable_hd_sizes', false)) {
                unset($sizes['2048x2048']);
                unset($sizes['1536x1536']);
            }

            return apply_filters('wp_sir_sizes', $sizes);
        }


        /**
         * @param string $sourcePath
         * @param array $size
         * @param string $sizeName
         * @param int $imageId
         *
         * @return string
         */

        public function generateThumbPath(
            $sourcePath,
            $size,
            $sizeName,
            $imageId
        ) {

            $sourceInfo = File::mb_pathinfo($sourcePath);

            $alreadyInJPG = in_array($sourceInfo['extension'], ['jpg', 'jpeg']);
            $isPNGToJPGEnabled = wp_sir_get_settings()['jpg_convert'];

            if ($isPNGToJPGEnabled && !$alreadyInJPG) {
                // To avoid conflict with existing original image/thumbnails 
                // under the same path and file name we preserve 
                // the original extension in the filename, i.e. 'chair-500x500.png.jpg'
                $extension = $sourceInfo['extension'] . '.jpg';
            } else {
                // No conversion needed.
                $extension = $sourceInfo['extension'];
            }

            $basename = sprintf(
                '%s-%dx%d.%s',
                $sourceInfo['filename'],
                $size['width'],
                $size['height'],
                $extension
            );

            $path = trailingslashit($sourceInfo['dirname']) . $basename;

            return apply_filters('wp_sir_thumbnail_save_path', $path, $sizeName, $imageId);
        }
    }


endif;
