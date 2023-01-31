<?php

namespace WP_Smart_Image_Resize\Filters;

use WP_Smart_Image_Resize\Helper;

class Filter_Subscriber
{
    /**
     * The list of events to subscribe.
     * @var array
     */
    protected $filters = [
        Calculate_Srcset::class,
        Image_Source::class,
        Background_Thumbnails_Regeneration::class,
        Generated_Sizes::class,
        Filter_Processable_Regenerate_Thumbnails::class,
        Custom_Sizes::class
    ];

    /**
     * Susbcribe events.
     */
    public function subscribe()
    {
        add_filter( 'wp_update_attachment_metadata', function($meta, $id){
            update_post_meta($id, '_old_image_meta', wp_get_attachment_metadata($id, true));
            return $meta;
        },10,2);

        if (! wp_sir_get_settings()['enable']) {
            return;
        }
        
        require_once path_join(__DIR__, 'Base_Filter.php');
        foreach ($this->filters as $class) {
            $class_name = Helper::get_class_short_name($class);
            require_once path_join(__DIR__, $class_name.'.php');
            if (class_exists($class)) {
                ( new $class )->listen();
            }
        }
    }
}

// Run.
( new Filter_Subscriber() )->subscribe();
