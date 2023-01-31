=== Smart Image Resize - Make WooCommerce Images the Same Size ===
Contributors: nlemsieh
Donate link: https://paypal.me/nlemsieh
Tags: woocommerce, product image resize, square image, same image size, cut-off image, cropped image, fix image crop, square thumbnail, resize image, uniform image, fix image size, resize thumbnails, image aspect ratio, fit image dimensions, insert watermark, image white space, image empty space
Requires at least: 4.0
Tested up to: 6.0
WC requires at least: 3.0
WC tested up to: 6.9
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Requires PHP: 5.6
Stable tag: 1.7.7

Make WooCommerce product images the same size and uniform without cropping. No more manual image editing and photo resizing.

== Description ==

[Smart Image Resize](https://sirplugin.com/) Makes your store look professional with product images that are all uniform and the same size without cropping.

- Zero-configuration.
- No more manual image editing and photo resizing.


### Lite Features

- Resize up to 150 images.
- Remove unwanted whitespace around image.
- Set a custom background color to match your site design
- Compress thumbnails for faster page load.
- Generate only needed thumbnails and delete unused ones.
- Choose which images to resize.

### Pro Features

- **♾ Unlimited Images:**  Unlimited images resizing.

-  **✈️ Convert to JPG format:** Reduce image file size and boost page speed.

- **🚀 Use WebP Images:** Speed up page load by reduce image file up to 90% while still providing transparency and the same quality.

- **🔒Insert watermark (beta):** Insert logo, name, SKU, and other info on all images, attracting new potential customers through search engines, and keep images safe from unauthorized use (especially if you sell digital products or if you want to keep the copyright safe for the images you publish online such as photos, pictures, comic strips, etc.)
- **👨‍💻 Get priority support:**
Get faster chat and email support.

[Check out Smart Image Resize PRO!](https://sirplugin.com?utm_source=wp&utm_medium=link&utm_campaign=lite_version)

### Usage

Once the plugin is installed, images newly uploaded will be automatically adjusted and resized.

To change your settings, navigate to WooCommerce > Smart Image Resize.

If you have already uploaded product images to Media Library, follow these steps to regenerate thumbnails:

1. Install Regenerate Thumbnails plugin.
2. Navigate to Tools > Regenerate Thumbnails.
4. Click the button **Regenerate Thumbnails For All Attachments** .

**IMPORTANT!** Make sure you purge cache if old images still showing.

To learn more, [check out the documentation](https://sirplugin.com/guide.html?utm_source=wp&utm_medium=link&utm_campaign=lite_version).

== Installation ==

1. Upload `smart-image-resize` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

 _**Note:** Make sure PHP Fileinfo extension is enabled in you server._

 == Frequently Asked Questions ==

 = Does the plugin automatically resize images on upload? =

Yes. 

= How can I resize images I already added to the media library? =

Follow these steps to resize existing images:

1. Install [Regenerate Thumbnails plugin](https://wordpress.org/plugins/regenerate-thumbnails).
2. Navigate to Tools > Regenerate Thumbnails.
3. Uncheck **Skip regenerating existing correctly sized thumbnails (faster)**
4. Finally, click on **Regenerate Thumbnails For All N Attachments** button.

**IMPORTANT!** Make sure you purge cache if old images still showing.

= Is the plugin compatible with WP CLI? =

Yes. You can use the command `wp media regenerate` to regenerate thumbnails to match your settings.

= Is the plugin compatible with Dokan Multivendor? =

Yes.

= I get an error when I upload an image =

Make sure PHP extension `fileinfo` is enabled on your server. 

= Still have questions? =

If you can't find the answer to your question, we may have posted it in [our FAQ](https://docs.sirplugin.com/faqs)

= How do I get support? =

If you have trouble with the plugin, [create a ticket](https://wordpress.org/support/plugin/smart-image-resize/) on the support forum and we'll make sure to look into it. 

If you are a pro user, [contact the support](https://sirplugin.com/contact.html) on the official plugin site.


== Screenshots ==

1. Before and after using the plugin.
2. Settings page.
3. Select sizes to generated.
4. Add custom background color of the new area.

== Changelog ==

= 1.7.7 =

* Improved compatibility with new themes and plugins
* Fixed an issue with the Trim whitespace's border size option not working properly in GD. 
* Fixed an issue in v1.7.6 causing some plugins' assets to not load properly.
* Declare compatibility with WooCommerce 6.9
* Minor bugfixes

= 1.7.6 =

* Deleted the option "Use WordPress cropping" as it seems to be causing some confusion for many users. To prevent specific sizes from being resized by the plugin use the filter `wp_sir_exclude_sizes` to return an array of size names you want to exclude.
* Fixed an issue with WebP files not deleted when the WebP feature is turned off.
* Declared compatibility with WooCommerce 6.3
* Added a work-around to fix a bug in Regenerate Thumbnails causing the latter to interfere with WPML.
* Stability improvements

= 1.7.5.3 =

* Fix a bug when background processing is trigged from the frontend.

= 1.7.5.2 =

* bugfixes

= 1.7.5 =

* Recheck and process skipped images in the background after the parent post is saved.
* Replace "Resize fit mode" option with "Use WordPress cropping".
* Fix issue with Trimming border size limited to original image size.
* Improve CMYK images handling
* Format error message in WP CLI and avoid halting execution.
* Fix an issue with CMYK profile not being converted to RGB in Imagick.
* Use another image processor as fallback when current one doesn't support WebP.
* Fix an issue with default image processor when Imagick doesn't support WebP. 
* Minor bugfixes 
* Stability improvement
* Performance improvement.

= 1.6.2 =

* Use another image processor as fallback when current one doesn't support WebP.
* Fix WebP Images not served in Ajax responses
* Fix an issue with default image processor when Imagick doesn't support WebP. 

= 1.6.1 =

* Add the ability to custom woocommerce default sizes.
* Stability improvement

= 1.6.0 =

* Add the ability to specify the resize fit mode for each size. 
* Stability improvement

= 1.5.5.1 =

* Stability improvement

= 1.5.5 =

* Fix color issue with some CMYK images.
* Fix faded images in some Imagick installs.

= 1.5.4 =

* Fix an issue with some themes not loading the correct image size.

= 1.5.3 =

* Stability improvement

= 1.5.2 =

* Fix thumbnail overwriten by WordPress when original image and thumbnail dimensions are identical
* Fix an issue with Flatsome using full size image instead of woocommerce_single for lazy load.
* Ignore sizes with 9999 dimension (unlimited height/width).
* Improve WebP availability detection.

= 1.5.1 =

* Use Imagick as default when available.
* Fix Avada not serving correct thumbnails on non-WooCommerce pages.
* Improve the user experience of the settings page. 


= 1.5.0 =

* Filter processed images in the media library toolbar
* Add filter `wp_sir_serve_webp_images`
* Improve Whitespace trimming tool  


= 1.4.10 =

* Declare compatibility with WooCommerce (v5.2)


= 1.4.9 =

* Use GD extension by default to process large images.


= 1.4.8 =

* Fixed an issue with some images in CMYK color.

= 1.4.7 =

* Fixed an issue with PNG-JPG conversion conflict
* Added support for WCFM plugin.
* Declared compatibility with WooCommerce (v5.0)
* Stability improvement


= 1.4.6 =

* Added tolerance level setting to trim away colors that differ slightly from pure white.
* Improved unwanted/old thumbnails clean up.


= 1.4.5 =

* Added compatibility with WooCommerce 4.9.x
* Stability improvement.

= 1.4.4 =

* Improved bulk-resizing using Regenerate Thumbnails plugin.
* Stability improvement.

= 1.4.3 =
* Fixed a minor issue with JPG images quality when compression is set to 0%.
* Stability improvement.

= 1.4.2.7 =
* Fixed an issue with UTF-8 encoded file names.

= 1.4.2.6 =

* Improved compatibility with WC product import tool.

= 1.4.2.5 =

* Fixed an issue when uploading non-image files occured in the previous update.


= 1.4.2.3 =

* Turned off cache busting by default.

= 1.4.2.2 =

* Fixed WebP images not loading in some non-woocommerce pages.

= 1.4.2.1 =

* Fixed trimming issue for some image profiles (Imagick).
* Added an option to specify trimmed image border.


= 1.4.2 =

* [Fixed] an issue with WebP images used in Open Graph image (og:image).
* Stability improvement

= 1.4.1 =

* Fixed a bug with WebP not installed on server.
* Fixed an issue with front-end Media Library.


= 1.4.0 =

* Added support for category images.
* Ability to decide whether to resize an image being uploaded directly from the Media Library uploader.
* Support for WooCommerce Rest API
* Developers can use the boolean parameter `_processable_image` to upload requests to automatically process images.
* Added filter `wp_sir_maybe_upscale` to prevent small images upscale.
* Process image attachment with valid parent ID.
* Fixed a tiny bug with compression only works for converted PNG-to-JPG images.
* Fixed an issue with srcset attribute caused non-adjusted images to load.
* Fixed an issue with trimmed images stretched when zoomed on the product page.
* Improved support for bulk-import products.
* Improved processing performances with Imagick.

= 1.3.9 =

* Fix compatibility issue with Dokan vendor upload interface.
* Performances improvement.

= 1.3.8 =

 * Added compatibility with WP 5.4
 * Added support for WP Smush
 * Stability improvement.

= 1.3.7 =

 * Stability improvement.


= 1.3.6 =

 * Fix a minor issue with image parent post type detection.
 * Added a new filter `wp_sir_regeneratable_post_status` to change regeneratable product status. Default: `publish`

= 1.3.5 =

 * Regenerate thumbnails speed improvement.


= 1.3.4 =

 * Stability improvement

= 1.3.3 =

 * fixed a minor issue with settings page.

= 1.3.2 =
 * Added thumbnails regeneration steps under "Regenerate Thumbnails" tab.

= 1.3.1 =
 * Fixed a minor bug in Regenerate Thumbnails tool.

= 1.3 =
 * Added a built-in tool to regenerate thumbnails.
 * woocommerce_single size is now selected by default.
 * Stability improvement.

= 1.2.4 =
 * Fix srcset images not loaded when WebP is enabled.

= 1.2.3 =
 * Set GD driver as default.
 * Stability improvement.

= 1.2.2 =
 * Prevent black background when converting transparent PNG to JPG.
 * Fixed random issue that causes WebP images fail to load.
 * Stability improvement.

= 1.2.1 =
* Added settings page link under Installed Plugins.

= 1.2.0 =
* Added Whitespace Trimming feature.
* Various improvements.

= 1.1.12 =

* Fixed crash when Fileinfo extension is disabled.

= 1.1.11 =

* Added support for Jetpack.

= 1.1.10 =

* Fixed conflict with some plugins.

= 1.1.9 =

* Prevent dynamic resize in WooCommerce.

= 1.1.8 =

* Handle WebP not installed.

= 1.1.7 =

* Fixed mbstring polyfill conflict with WP `mb_strlen` function


= 1.1.6 =
* Added polyfill for PHP mbstring extension

= 1.1.5 =
* Force square image when height is set to auto.

= 1.1.4 =
* Fixed empty sizes list

= 1.1.3 =
* Fixed empty sizes list

= 1.1.2 =

* Added settings improvements
* Added processed images notice.

= 1.1.1 =

* Added fileinfo and PHP version notices
* Improved settings page experience.

= 1.1.0 =

* Introducing Smart Image Resize Pro features
* Various improvements

= 1.0.13 =

* Fixed some images not resized correctly.

= 1.0.12 =

* Minor bugfix

= 1.0.11 =

* Errors messages now are displayed in media uploader. This will help debug occured problems while resizing.

= 1.0.10 =

* The PHP Fileinfo extension is required. Now you can see notice when it isn't enabled.

= 1.0.9 =

* Fixed bug that prevents upload of non-image files to the media library.

= 1.0.8 =

* Skip woocommerce_single resize

= 1.0.7 =

* Stability improvement

= 1.0.6 =

* Bugfix


= 1.0.5 =

* Bugfix

= 1.0.4 =

* Removed deprecated option.

= 1.0.3 =

* Small images resize improvement.

= 1.0.2 =

Improve stability

= 1.0.1 =

- Add ability to add custom color in settings.
- Fixbug for some PHP versions.

= 1.0.0 =

* Public Release

 == Upgrade Notice ==

  = 1.6.0 =

* Added the ability to use a specific resizing mode for each size.