<?php
/**
 * File Type: Services Page Element
 */
if ( ! class_exists('Wp_dp_Images_Gallery_Element') ) {

    class Wp_dp_Images_Gallery_Element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_images_gallery_element_html', array( $this, 'wp_dp_images_gallery_element_html_callback' ), 11, 2);
        }

        /*
         * Output features html for frontend on listing detail page.
         */

        public function wp_dp_images_gallery_element_html_callback($post_id , $view_place = '') {
            global $wp_dp_plugin_options;
            $content_gallery = wp_dp_element_hide_show($post_id, 'content_gallery');
            
            
            if($view_place == 'view-5' && $content_gallery != 'on' ){
                return;
            }
            
         
            $html = '';
            wp_enqueue_style('flexslider');
            wp_enqueue_script('flexslider');
            wp_enqueue_script('flexslider-mousewheel');
            $gallery_limit = wp_dp_cred_limit_check($post_id, 'wp_dp_transaction_listing_pic_num');
            $gallery_ids_list = get_post_meta($post_id, 'wp_dp_detail_page_gallery_ids', true);
            $gallery_pics_allowed = get_post_meta($post_id, 'wp_dp_transaction_listing_pic_num', true);
            $listing_type = get_post_meta($post_id, 'wp_dp_listing_type', true);
            $listing_type_id = '';
            if ( $listing_type != '' ) {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type", 'post_status' => 'publish' ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            }
            $element_title = get_post_meta( $listing_type_id, 'wp_dp_listing_type_title_image_gallery', true );

            if ( is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 0 && $gallery_pics_allowed > 0 ) {
                ?>
                <div class="main-post">
                    <div id="slider-<?php echo absint($post_id); ?>" class="listing-flexslider flexslider">
                        <div class="wp-dp-button-loader spinner">
                            <div class="bounce1"></div>
                            <div class="bounce2"></div>
                            <div class="bounce3"></div>
                        </div>
                        <ul class="slides">
                            <?php
                            $gallery_counterr = 1;
                            foreach ( $gallery_ids_list as $gallery_idd ) {
                                if ( isset($gallery_idd) && $gallery_idd != '' ) {
                                    if ( wp_get_attachment_url($gallery_idd) ) {
                                        $image = wp_get_attachment_image_src($gallery_idd, 'wp_dp_media_12');
                                        ?>
                                        <li>
                                            <img src="<?php echo esc_url($image[0]); ?>" alt="" />
                                        </li>
                                        <?php
                                        if ( $gallery_limit == $gallery_counterr ) {
                                            break;
                                        }
                                        $gallery_counterr ++;
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php if(is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 1 && $gallery_pics_allowed > 0){?>
                    <div id="carousel-<?php echo absint($post_id); ?>" class="listing-carousel-flexslider flexslider">
                        <ul class="slides">
                            <?php
                            $gallery_counter = 1;
                            foreach ( $gallery_ids_list as $gallery_id ) {
                                if ( isset($gallery_id) && $gallery_id != '' ) {
                                    if ( wp_get_attachment_url($gallery_id) ) {
                                        ?>
                                        <li>
                                            <?php echo wp_get_attachment_image($gallery_id, 'wp_dp_media_7'); ?>
                                        </li>
                                        <?php
                                        if ( $gallery_limit == $gallery_counter ) {
                                            break;
                                        }
                                        $gallery_counter ++;
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>

                <?php
                $wp_dp_cs_inline_script = '
				jQuery(window).ready(function () {
					 "use strict"; 
					 jQuery(\'#slider-' . $post_id . '\').flexslider({
					  animation: "slide",
					  controlNav: false,
					  animationLoop: false,
					  slideshow: false,
					  drag: true,
					  mousewheel: false,
					  touch:true,
					  smoothHeight: true,
					  directionNav: false,
					  sync: "#carousel",
					  start: function(slider){
						jQuery("#slider-' . $post_id . '").show();
						jQuery("#slider-' . $post_id . '").removeClass("cs-loading");
					  }
					 });
					 jQuery(\'#carousel-' . $post_id . '\').flexslider({   
					  animation: "slide",
					  controlNav: false,
					  animationLoop: false,
					  slideshow: false,
					  directionNav: true,
					  itemWidth: 67,
					  drag: true,
					  mousewheel: false,
					  touch:true,
					  itemMargin: 12,
					  asNavFor: "#slider-' . $post_id . '",
					  start: function(slider){
					   jQuery("#carousel-' . $post_id . '").show();
					  
					  }
					 });
					 function sliderResize(){
					  var slider2 = jQuery(\'#carousel-' . $post_id . '\').data("flexslider");
					  if($(window).width() < 1024 && $(window).width() > 767){
					   slider2.vars.itemWidth = 99;
					   slider2.doMath();
					  }
					  if($(window).width() < 768 && $(window).width() > 668){
					   slider2.vars.itemWidth = 101;
					   slider2.doMath();
					  }
					  if($(window).width() < 668 && $(window).width() > 500){
					   slider2.vars.itemWidth = 102;
					   slider2.doMath();
					  }
					  if($(window).width() < 500 && $(window).width() > 450){
					   slider2.vars.itemWidth = 109;
					   slider2.doMath();
					  }
					  if($(window).width() < 450){
					   slider2.vars.itemWidth = 125;
					   slider2.doMath();
					  }
					  if($(window).width() < 385 && $(window).width() > 360){
					   slider2.vars.itemWidth = 104;
					   slider2.doMath();
					  }
					  if($(window).width() < 360){
					   slider2.vars.itemWidth = 93;
					   slider2.doMath();
					  }
					 }
					
                    jQuery(window).bind("resize", function() { 
						setTimeout(function(){
							var slider1 = jQuery(\'#slider-' . $post_id . '\').data("flexslider");  
							slider1.resize();
						  }, 1000);
						sliderResize()
					  
					 });
					 });';
                echo '<script type="text/javascript">' . $wp_dp_cs_inline_script . '</script>';
            }

            echo force_balance_tags($html);
        }

    }

    global $wp_dp_images_gallery_element;
    $wp_dp_images_gallery_element = new Wp_dp_Images_Gallery_Element();
}