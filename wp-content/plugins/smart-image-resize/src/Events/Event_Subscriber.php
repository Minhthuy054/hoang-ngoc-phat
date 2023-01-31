<?php

namespace WP_Smart_Image_Resize\Events;

use WP_Smart_Image_Resize\Helper;

class Event_Subscriber
{
    /**
     * The list of events to subscribe.
     * @var array
     */
    protected $events = [
        Image_Deleted::class,
        Image_Inserted::class
    ];

    /**
     * Susbcribe events.
     */
    public function subscribe()
    {
        if ( ! wp_sir_get_settings()[ 'enable' ] ) {
            return;
        }
        require_once path_join( __DIR__, 'Base_Event.php' );

        foreach ( $this->events as $class ) {
            $class_name = Helper::get_class_short_name( $class );
            require_once path_join( __DIR__, $class_name . '.php' );
            if ( class_exists( $class ) ) {
                ( new $class )->listen();
            }
        }
    }
}

( new Event_Subscriber )->subscribe();
