<?php

namespace WP_Smart_Image_Resize;

trait Singleton_Trait
{
/**
     * Singleton class instance holder
     *
     * @since 1.0.0
     *
     * @var object
     */

    private static $instance = null;

    /**
     * Make a class instance
     *
     * @since 1.0.0
     *
     * @return object
     */
    public static function instance() {
        if ( is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}