<?php

namespace WP_Smart_Image_Resize;

final class Quota
{
    /**
     * Credit limit.
     */
    const QUOTA = 150;

    /**
     * Warn before exceeding quota threshold.
     */
    const QUTA_EXCEEDING_THRESHOLD = 100;

    /**
     * Get initial credits.
     *
     * 1 credit =  1 image attachment ( unlimited thumbnails ).
     */
    public static function get_initial_credits()
    {
        return self::QUOTA;
    }

    /**
     * Incredement credit for the given image attachment.
     *
     * @param int $image_id
     *
     * @return void
     */
    public static function consume($image_id)
    {
        $processed_images = self::get_processed_images();

        if (isset($processed_images[$image_id])) {
            $processed_images[$image_id]++;
        } else {
            $processed_images[$image_id] = 1;
        }

        update_option('wp_sir_processed_attachments', $processed_images);
    }

    /**
     * Get proceeded images array.
     *
     * @return array
     */
    public static function get_processed_images()
    {
        return (array) get_option('wp_sir_processed_attachments', []);
    }

    /**
     * Determine whether the quota is exceeded.
     *
     * @return bool
     */
    public static function isExceeded()
    {
        return self::get_consumed() >= self::QUOTA;
    }

    /**
     * Get the total of consumed credits.
     * @return int
     */
    public static function get_consumed()
    {
        return count(self::get_processed_images());
    }

    /**
     * Check if the quora is exceeding soon.
     * @return bool
     */
    public static function is_exceeding_soon()
    {
        $consumed = self::get_consumed();

        return $consumed > self::QUTA_EXCEEDING_THRESHOLD && $consumed < self::QUOTA;
    }

    public static function show_quota_status()
    {
        ?>
      <div class="wpsirQuotaStatus">

        <span><?php echo self::get_consumed() ?> image(s) of <?php echo self::get_initial_credits() ?> processed <span class="wp-sir-help-tip" title='To see processed images, apply the filter "Smart Resize: Processed" in your media library.'></span>
 ( <a target="_blank" href="https://sirplugin.com/#pro">Upgrade to PRO</a> for unlimited images ).</span>
        <span class="wpsirQuotaStatusProgressBar <?php echo self::is_exceeding_soon() ? 'isExceeding' : '' ?>
        
        <?php echo self::isExceeded() ? 'isExceeded' : '' ?>
        "><span style="width: <?php echo self::get_consumed() ?>px"></span></span>
        
      </div>

      <?php
    }
}
