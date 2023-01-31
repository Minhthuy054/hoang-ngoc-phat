<?php

/*
|--------------------------------------------------------------------------
| Add support for Regenerate Thumbnails.
|--------------------------------------------------------------------------
*/

// set Regenerate Thumbnails to regenerate all image thumbnails by default.
add_filter('regenerate_thumbnails_options_onlymissingthumbnails', '__return_false');
