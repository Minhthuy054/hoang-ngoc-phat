jQuery(document).ready(function () {
    $('[data-toggle="popover_html"]').popover({
        placement: 'bottom',
        container: 'body',
        trigger: 'hover',
        html: true,
        content: function () {
            return $(this).next('.ratings-popover-content').html();
        }
    });
    //=== sidebar margin ===
    function sideBarMargin(element) {
        this.thisElement = element;
        this.ListingTop = $(".listing-detail.detail-v5 .listing-detail-title-area").position().top + parseInt($(".listing-detail.detail-v5 .listing-detail-title-area").css("padding-top")) - 5;
        this.sideBarTop = $(".listing-detail.detail-v5 .sidebar").position().top;
    }
    sideBarMargin.prototype.marginAdd = function () {
        this.difference = this.ListingTop - this.sideBarTop;
        $(".listing-detail.detail-v5 .sidebar").css("margin-top", "0");
    };
    sideBarMargin.prototype.marginRemove = function () {
        this.difference = 0;
        $(".listing-detail.detail-v5 .sidebar").css("margin-top", "0");
    };
    //=== sidebar margin ===
    function StickySidebar(element) {
        this.thisElement = element;
        this.isSticky = false;
        this.stickyCheck = function (stickVal) {
            this.isSticky = stickVal;
        };
        this.wpAdminBar = function () {
            this.wpAdminBarVar = $("#wpadminbar").height();
            return this.wpAdminBarVar;
        };
    }
    StickySidebar.prototype.stickyActive = function () {
        this.thisElement.css("top", this.wpAdminBar());
        sidebarMargin.marginRemove();
        this.thisElement.addClass("stickySideBar-active");
        this.stickyCheck(true);
    };
    StickySidebar.prototype.stickyDisable = function () {
        this.thisElement.css("top", "");
        this.thisElement.removeClass("stickySideBar-active");
        this.stickyCheck(false);
        sidebarMargin.marginAdd();
    };
    StickySidebar.prototype.stickyStop = function () {
        this.thisElement.parent().addClass("stickySideBar-stop");
    };
    StickySidebar.prototype.stickyStopRemove = function () {
        this.thisElement.parent().removeClass("stickySideBar-stop");
    };
    if (($(".sticky-sidebar").length > 0) && ($(window).width() > 998)) {
        var sidebarMargin = new sideBarMargin($(".sticky-sidebar"));
        sidebarMargin.marginAdd();
    }
	if($('.listing-detail.detail-v5 .sticky-sidebar').children('.listing-opening-hours').length > 0) {
		$(".listing-detail.detail-v5 .sticky-sidebar").removeClass('no-mortgage-calc');
		$(".listing-detail.detail-v5 .sticky-sidebar").addClass('has-mortgage-calc');
	}
	if($('.listing-detail.detail-v5 .sticky-sidebar').children('.flickr-gallery-slider').length > 0) {
		$(".listing-detail.detail-v5 .sticky-sidebar").removeClass('no-mortgage-calc');
		$(".listing-detail.detail-v5 .sticky-sidebar").addClass('has-mortgage-calc');
	}	
	if($('.listing-detail.detail-v5 .sticky-sidebar').children('.widget-map-sec').length > 0) {

		$(".listing-detail.detail-v5 .sticky-sidebar").removeClass('no-mortgage-calc');
		$(".listing-detail.detail-v5 .sticky-sidebar").addClass('has-mortgage-calc');
	}
	if($('.listing-detail.detail-v5 .sticky-sidebar').children('.contact-member-form.member-detail').length > 0) {
 
		$(".listing-detail.detail-v5 .sticky-sidebar").removeClass('no-mortgage-calc');
		$(".listing-detail.detail-v5 .sticky-sidebar").addClass('has-mortgage-calc');
	}	
	
	
    $(window).load(function () {
        if ($(".listing-detail.detail-v5 .sticky-sidebar.no-mortgage-calc").length > 0) {
            var stickySideBar = new StickySidebar($(".sticky-sidebar"));
            var $window = $(window);
            var stickyEndPoint, stickyStart;
            stickyStart = stickySideBar.thisElement.offset().top - $(".detail-nav-wrap.detail-v5 .detail-nav.detail-nav-map").outerHeight() - parseInt($(".listing-detail.detail-v5 .listing-detail-title-area").css("padding-top"));
            stickyEndPoint = $(".listing-detail.detail-v5 .page-content").outerHeight() + 20;
            $( document ).ajaxComplete(function() {
                stickyEndPoint = $(".listing-detail.detail-v5 .page-content").outerHeight() + 20;
            });
            if ($("#wpadminbar").length > 0) {
                stickyStart = stickyStart - $("#wpadminbar").height() + 5;
            }
            //target element offset for the sticky instance
            if ($("#wpadminbar").length > 0) {
                stickyEndPoint = stickyEndPoint - $("#wpadminbar").height() + 5;
            }
            if ($window.width() > 998) {
                $window.scroll(function () {
                    var window_top = $window.scrollTop();
                    if (window_top > stickyStart && window_top < stickyEndPoint && stickySideBar.isSticky == false) {
                        stickySideBar.stickyActive();
                        stickySideBar.stickyStopRemove();
                    }else if ((window_top > stickyEndPoint || window_top < stickyStart) && stickySideBar.isSticky == true) {
                        stickySideBar.stickyDisable();

                    }
                    if (window_top > stickyEndPoint && stickySideBar.isSticky == false) {
                        stickySideBar.stickyStop();
                        sidebarMargin.marginRemove();
                    }
                });
            }
            //window resize sticky bind or unbind
            $(window).resize(function () {
                if ($window.width() < 998) {
                    stickySideBar.stickyDisable();
                    sidebarMargin.marginRemove();
                    $window.scroll(function () {
                        stickySideBar.stickyDisable();
                        sidebarMargin.marginRemove();
                    });
                } else {
                    var window_top;
                    window_top = $window.scrollTop();
                    if (window_top > stickyStart && window_top < stickyEndPoint && stickySideBar.isSticky == false) {
                        stickySideBar.stickyActive();
                        sidebarMargin.marginRemove();
                    }
                    $window.scroll(function () {
                        window_top = $window.scrollTop();
                        if (window_top > stickyStart && window_top < stickyEndPoint && stickySideBar.isSticky == false) {
                            stickySideBar.stickyActive();
                            sidebarMargin.marginRemove();
                        } else if ($window.width() > 998 && window_top < stickyStart) {
                            sidebarMargin.marginAdd();
                        }
                    });
                }
            });
        }
        // sticky sidebar
    });
    if (jQuery(".map-checkboxes .swiper-container").length > 0) {

        new Swiper(".map-checkboxes .swiper-container", {
            spaceBetween: 15,
            nextButton: ".map-checkboxes .swiper-checkbox-next",
            prevButton: ".map-checkboxes .swiper-checkbox-prev",
            slidesPerView: 5,
            speed: 500,
            onInit: function (swiper) {
                jQuery.fn.matchHeight._update();
            },
            breakpoints: {
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 20
                },
                998: {
                    slidesPerView: 4,
                    spaceBetween: 20
                },
                767: {
                    slidesPerView: 3,
                    spaceBetween: 15
                },
                540: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },
                380: {
                    slidesPerView: 1,
                    spaceBetween: 15
                }
            }
        });
    }

    if (jQuery(".map-fullwidth .map-checkboxes-v2 .swiper-container").length > 0) {
        new Swiper(".map-fullwidth .map-checkboxes-v2 .swiper-container", {
            spaceBetween: 0,
            nextButton: ".map-fullwidth .map-checkboxes-v2 .swiper-checkbox-next",
            prevButton: ".map-fullwidth .map-checkboxes-v2 .swiper-checkbox-prev",
            slidesPerView: 3,
            speed: 500
        });
    }
    if (jQuery(".sidebar .map-checkboxes-v2 .swiper-container").length > 0) {
        new Swiper(".sidebar .map-checkboxes-v2 .swiper-container", {
            spaceBetween: 0,
            nextButton: ".sidebar .map-checkboxes-v2 .swiper-checkbox-next",
            prevButton: ".sidebar .map-checkboxes-v2 .swiper-checkbox-prev",
            slidesPerView: 3,
            speed: 500,
            breakpoints: {
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                998: {
                    slidesPerView: 6,
                    spaceBetween: 20
                },
                767: {
                    slidesPerView: 4,
                    spaceBetween: 15
                },
                540: {
                    slidesPerView: 3,
                    spaceBetween: 15
                },
                380: {
                    slidesPerView: 2,
                    spaceBetween: 15
                }
            }
        });
    }
//End checkboxes Slider



});

function wp_dp_load_yelp_places(listing_id) {

    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: wp_dp_globals.ajax_url,
        data: "action=wp_dp_listing_yelp_results&listing_id=" + listing_id,
        success: function (response) {
            jQuery('#listing_detail_yelp_result_' + listing_id).removeClass('listing-detail-section-loader');
            if (response.status == true) {
                jQuery('#listing_detail_yelp_result_' + listing_id).html(response.result);
            }
        },
        error: function (response) {
            wp_dp_load_yelp_places(listing_id);
        }
    });
}
function wp_dp_load_walk_score(listing_id, view) {

    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: wp_dp_globals.ajax_url,
        data: "action=wp_dp_listing_walk_score_results&listing_id=" + listing_id + '&view=' + view,
        success: function (response) {
            jQuery('#listing_detail_walk_score_result_' + listing_id).removeClass('listing-detail-section-loader');
            if (response.status == true) {
                jQuery('#listing_detail_walk_score_result_' + listing_id).html(response.result);
            }
        },
        error: function (response) {
            wp_dp_load_walk_score(listing_id, view);
        }
    });
}

function wp_dp_load_sidebar_map_html(listing_id, view) {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: wp_dp_globals.ajax_url,
        data: "action=wp_dp_listing_sidebar_map&listing_id=" + listing_id + '&view=' + view,
        success: function (response) {
            jQuery('#listing_detail_sidebar_map_' + listing_id).removeClass('listing-detail-section-loader');
            if (response.status == true) {
                jQuery('#listing_detail_sidebar_map_' + listing_id).html(response.result);
            }
            
            //initialize();
        },
        error: function (response) {
            //wp_dp_load_sidebar_map_html(listing_id, view);
        }
    });
}