<?php

namespace WP_Smart_Image_Resize;

trait Runtime_Config_Trait
{


    function checkMemoryLimit()
    {

        if ( ! apply_filters( 'wp_sir_allow_runtime_memory_check', true ) ) {
            return;
        }

        @ini_set( 'memory_limit', '1024M' );
        defined( 'WP_MEMORY_LIMIT' ) or define( 'WP_MEMORY_LIMIT', '1024M' );

    }

    function resetTimeLimit()
    {
        if ( ! apply_filters( 'wp_sir_allow_time_limit_reset', true ) ) {
            return;
        }

        @set_time_limit( 0 );

    }
}