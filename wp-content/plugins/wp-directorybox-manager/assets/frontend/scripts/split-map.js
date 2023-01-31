var $ = jQuery;
var ajaxRequest;

jQuery(document).ready(function () {

    // listing split map 

    // sticky map tooltip
    if ($(".wp-dp-split-map-wrap").length > 0) {
        // variables
        //jQuery('.main-header').addClass('dashboard-fixed-header');
        var boxWrap = $(".wrapper-boxed");
        var wrapWidth = boxWrap.outerWidth();
        var wrapMarginTop = boxWrap.css("margin-top");
        var wrapMarginBottom = boxWrap.css("margin-bottom");
        var headerOffset = $("#header").offset().top;
        var windowWidth = $(window).width();
        var offsetMargin = (windowWidth - wrapWidth) / 2;
        var holderWidth = $(".wp-dp-split-map-wrap .split-map-holder").width();
        var holderWidthOffset = $(".wp-dp-split-map-wrap .split-map-holder").width() - offsetMargin;
        var mapBottomOffset = $(window).height();
        var mapBottomOffset2 = mapBottomOffset - $("#header").height();
        var rectRight = "rect(" + headerOffset + 'px' + ", " + holderWidthOffset + 'px' + ", " + mapBottomOffset + 'px' + ", 0)";
        var rectLeft = "rect(" + headerOffset + 'px' + ", " + holderWidth + 'px' + ", " + mapBottomOffset + 'px' + ",  " + offsetMargin + 'px' + ")";

        var headerHeight = $("#header").height();
        var stickyHeight = headerHeight - $(".sticky-header").height;
        var windowHeight = $(window).height();
        var mapSplitOffsetbottom = $(".split-map-container").height();
        if (wrapMarginTop == undefined || wrapMarginBottom == undefined) {
            wrapMarginTop = 0;
            wrapMarginBottom = 0;
        }

        function StickyMapTools(element) {
            this.thisElement = element;
            this.isSticky = false;
            this.stickyCheck = function (stickVal) {
                this.isSticky = stickVal;
            }
            this.wpAdminBar = function () {
                this.wpAdminBarVar = $("#wpadminbar").height();
                return this.wpAdminBarVar;
            }
            this.getHeader = function () {
                this.getHeaderHeight = $("#header").height();
                return this.getHeaderHeight;
            }
        }
        StickyMapTools.prototype.stickyToolActive = function () {
            this.thisElement.css("top", this.wpAdminBar() + this.getHeader() + parseInt(wrapMarginTop));
            this.thisElement.addClass("toolSticky-active");
            this.stickyCheck(true);
        };
        StickyMapTools.prototype.stickyToolDisable = function () {
            this.thisElement.css("top", '');
            this.thisElement.removeClass("toolSticky-active");
            this.stickyCheck(false);
        };
        var stickyTools = new StickyMapTools($(".map-actions, .listing-records-sec"));
        if ($(".wp-dp-split-map-wrap.split-map-left,.wp-dp-split-map-wrap.split-map-right").length > 0) {
            stickyTools.stickyToolActive();
        }

        $(".wp-dp-split-map-wrap.split-map-right .wp-dp-ontop-gmap,.wp-dp-split-map-wrap.split-map-left .wp-dp-ontop-gmap").height(windowHeight);
        //========= on scroll map tools===========
        var w = $(window);
        var wrapOffset = $(".wp-dp-split-map-wrap").offset();
        var counter;
        var elementOffset = wrapOffset.top - w.scrollTop();
        counter = elementOffset;
        if ($(".wp-dp-split-map-wrap.split-map-left,.wp-dp-split-map-wrap.split-map-right").length > 0) {
            var filterHeight = $(".filters-sidebar").height();
            $(".wp-dp-split-map-wrap .filters-sidebar + .col-lg-9").css("min-height", w.height() - headerHeight);
            // clip map for box view
            if ($(".wrapper-boxed").length > 0) {
                // for map on right           
                $(".wp-dp-split-map-wrap.split-map-right .split-map-holder").css("clip", rectRight);
                $(".wp-dp-split-map-wrap.split-map-fixed.split-map-right .map-actions").css({
                    "right": offsetMargin + 10, "left": "auto"
                });
                $(".wp-dp-split-map-wrap.split-map-fixed.split-map-right .listing-records-sec").css({
                    "right": offsetMargin + $(".map-actions").width() + 20,
                    "left": "auto"});
                // for map on right
                // for map on left
                $(".wp-dp-split-map-wrap.split-map-left .split-map-holder").css("clip", rectLeft);
                $(".wp-dp-split-map-wrap.split-map-fixed.split-map-left .map-actions").css({"left": offsetMargin + 10, "right": "auto"});
                $(".wp-dp-split-map-wrap.split-map-fixed.split-map-left .listing-records-sec").css({
                    "left": offsetMargin + $(".map-actions").width() + 20,
                    "right": "auto"});
                // for map on left
            }
            // clip map for box view
            $(window).scroll(function () {
                if ($(".sticky-header").length > 0) {
                    var stickyTop = $(".sticky-header").offset().top;
                    var winTop = $(window).scrollTop();
                    if (counter > 0) {
                        if (counter < stickyTop - winTop + $(".sticky-header").height()) {
                            $(".map-actions, .listing-records-sec").css("top", $(".sticky-header").height() + $("#wpadminbar").height());
                        } else {
                            $(".map-actions, .listing-records-sec").css("top", counter);
                            counter = wrapOffset.top - w.scrollTop();
                        }
                    } else if (counter > elementOffset || counter < 0) {
                        counter = stickyTop - winTop + $(".sticky-header").height();
                    }
                    if (wrapOffset.top - winTop - $(".sticky-header").height() > 0) {
                        counter = wrapOffset.top - winTop;
                        $(".map-actions, .listing-records-sec").css("top", counter);
                    }
                    var togglerTop = $(".split-map-toggler").offset().top - winTop;
                    if (winTop < mapSplitOffsetbottom - $("#header").outerHeight() - $("#footer").outerHeight() - $(".company-logo-holder").outerHeight() - 450) {
                        $(".split-map-toggler").css("margin-top", winTop - 20);
                    }
                    if ($(".wrapper-boxed").length > 0) {
                        if (winTop + headerHeight + parseInt(wrapMarginTop) + parseInt(wrapMarginBottom) + $("#footer").height() + 150 > $("#footer").offset().top - headerHeight - $("#footer").height() - $(".company-logo-holder .company-logo").height()) {
                            var rectRight = "rect(" + headerOffset + 'px' + ", " + holderWidthOffset + 'px' + ", " + mapBottomOffset2 + 'px' + ", 0)";
                            $(".wp-dp-split-map-wrap.split-map-right .split-map-holder").css("clip", rectRight);
                            var rectLeft = "rect(" + headerOffset + 'px' + ", " + holderWidth + 'px' + ", " + mapBottomOffset2 + 'px' + ",  " + offsetMargin + 'px' + ")";
                            $(".wp-dp-split-map-wrap.split-map-left .split-map-holder").css("clip", rectLeft);
                        } else {
                            var rectRight = "rect(" + headerOffset + 'px' + ", " + holderWidthOffset + 'px' + ", " + mapBottomOffset + 'px' + ", 0)";
                            $(".wp-dp-split-map-wrap.split-map-right .split-map-holder").css("clip", rectRight);
                            var rectLeft = "rect(" + headerOffset + 'px' + ", " + holderWidth + 'px' + ", " + mapBottomOffset + 'px' + ",  " + offsetMargin + 'px' + ")";
                            $(".wp-dp-split-map-wrap.split-map-left .split-map-holder").css("clip", rectLeft);
                        }
                    }
                }
            });
        }
        //========= on scroll map tools===========

    }

});
// sticky map tooltip
// map toggler
/* $(".split-map-toggler").css({"top": vCenter});
 $(".split-map-toggler").click(function(){
 $(this).toggleClass("active");
 $(".wp-dp-split-map-wrap .filters-sidebar").toggleClass("active");
 });*/

var windowHeight = jQuery(window).height();
var headerHeight = jQuery("#header").height();
var rHeight = windowHeight - headerHeight;
var vCenter = rHeight / 2;
jQuery(".split-map-toggler").css({"top": vCenter});
jQuery(".split-map-toggler").click(function () {
    jQuery(this).toggleClass("active");
    jQuery(".wp-dp-split-map-wrap .filters-sidebar").toggleClass("active");
});


jQuery(document).ajaxComplete(function () {
    //splitMap();
    var windowHeight = jQuery(window).height();
    var headerHeight = jQuery("#header").height();
    var rHeight = windowHeight - headerHeight;
    var vCenter = rHeight / 2;
    jQuery(".split-map-toggler").css({"top": vCenter});
    jQuery(".split-map-toggler").click(function () {
        jQuery(this).toggleClass("active");
        jQuery(".wp-dp-split-map-wrap .filters-sidebar").toggleClass("active");
    });
});

jQuery(document).on("click", ".wp-dp-quick-view-dev", function (e) {
    "use strict";

    var id = jQuery(this).data('id');
    var rand_id = jQuery(this).data('rand');
    var listings_excerpt_length = jQuery(this).data('listings_excerpt_length');
    var quickView = jQuery('.quick-view-content');
    quickView.html("<div class='fancy-spinner'></div>");
        jQuery.ajax({
            type: "POST",
            dataType: "html",
            url: wp_dp_globals.ajax_url,
            data: "action=quick_view_content&listing_id=" + id + '&listing_rand="' + rand_id + '&listings_excerpt_length=' + listings_excerpt_length,
            success: function (response) {
                quickView.html("");
                quickView.html(response);
                var slider1 = jQuery('#slider-'+ id).data("flexslider");  
                slider1.resize();
            }
        });
});

$(document).ajaxComplete(function () {
    if (jQuery(".modal-dialog").length > 0) {
        function reposition() {
            var modal = $(this),
                    dialog = modal.find('.modal-dialog');
            modal.css('display', 'block');

            // Dividing by two centers the modal exactly, but dividing by three 
            // or four works better for larger screens.
            dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
        }
        // Reposition when a modal is shown
        $('.modal').on('show.bs.modal', reposition);
        // Reposition when the window is resized
        $(window).on('resize', function () {
            $('.modal:visible').each(reposition);
        });
        var slider1 = jQuery("#slider").data("flexslider");
        if (slider1 != '' && slider1 != 'undefined' && slider1 != undefined){
            slider1.resize();
        }
    }

});



