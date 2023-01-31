<?php

namespace WP_Smart_Image_Resize\Plugin_Support;

/*
|--------------------------------------------------------------------------
| Add support for Jetpack Photon.
|--------------------------------------------------------------------------
*/

// Disable photon as it doesn't support the plugin features.
add_filter('jetpack_photon_skip_image', '__return_true');

// Do not use remotely-resized images with Jetpack Photon.
add_filter('jetpack_photon_override_image_downsize', '__return_true', 19);
