/**  jquery document.ready functions */
var $ = jQuery;
var ajaxRequest;

jQuery(document).ajaxComplete(function () {
    if (jQuery(".user-list").length > 0) {
        jQuery(".user-list").mCustomScrollbar({
            axis: "yx",
            scrollButtons: {
                enable: true
            },
            theme: "3d",
            scrollbarPosition: "outside"
        });
    }
});

// function equalHeight(target){
//     target.matchHeight();
// }
// equalHeight($(".listing-grid"));
function equalHeight(element) {
    this.thisElement = element;
    this.thisTarget = $(this.thisElement);
}
equalHeight.prototype.equalHeightActive = function () {
    this.thisTarget.matchHeight();
};
equalHeight.prototype.equalHeightActiveSubChild = function (subChild) {
    this.subTarget = this.thisTarget.find(subChild);
    this.subTarget.matchHeight();
};
equalHeight.prototype.equalHeightDisable = function () {
    $(this.thisTarget).matchHeight({
        remove: true
    });
};
equalHeight.prototype.equalHeightChildDisable = function (subChild2) {
    $(this.thisTarget).find(subChild2).matchHeight({
        remove: true
    });
};
// match height variables
var propertGridEqual,
    propertMediumModernEqual,
    propertMediumAdvanceEqual,
    propertAdvanceEqual,
    propertModernEqual,
    propertDefaultEqual,
    blogGridEqual,
    memberGridEqual,
    memberInfoEqual,
    topLocationsEqual,
    topLocationsEqual,
    propertGridModernEqual,
    propertModernv1Equal,
    propertGridModernEqualV3,
    propertGridMasnory,
    dsidxListings;
// match height variables
jQuery(document).ready(function ($) {

    jQuery(document).on("click", "#play-video", function (e) {
        "use strict";
        var id = jQuery(this).data('id');
        var videoObj = jQuery(this).closest('.video-fit-holder');
        videoObj.find("i").removeClass('icon-play_arrow');
        videoObj.find("i").addClass('fancy-spinner');
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: wp_dp_globals.ajax_url,
            data: "action=wp_dp_detail_video_render&listing_id=" + id,
            success: function (response) {
                videoObj.html(response);
                videoObj.fitVids();
            }
        });
    });


});

jQuery(document).on('change', '.listing-sold-check', function () {
    "use strict";
    var _this = $(this);
    var _parent = _this.parents('.sold-listing-box');
    var id = _this.attr('data-id');

    if (_this.is(':checked')) {
        var conf = confirm(wp_dp_globals.listing_sold_confirm);
        if (conf == true) {
            _parent.html('<span class="listing-loader"><i class="fancy-spinner"></i></span>');
            var ajax_url = wp_dp_globals.ajax_url;
            var data_vals = 'prop_id=' + id + '&action=wp_dp_listing_sold_check';
            $.ajax({
                url: ajax_url,
                method: "POST",
                data: data_vals,
                dataType: "json"
            }).done(function (response) {
                if (typeof response.html !== 'undefined') {
                    _parent.html(response.html);
                }
                wp_dp_show_response(response);
            }).fail(function () {
                var resp = {
                    type: "error",
                    msg: wp_dp_globals.listing_sold_action_failed
                };
                wp_dp_show_response(resp);
            });
        } else {
            _this.prop('checked', false);
        }
    }
});

jQuery(document).ready(function ($) {
    if ($(".listing-medium.modern").length > 0) {
        var imageUrlFind = $(".listing-medium.modern .img-holder").css("background-image").match(/url\(["']?([^()]*)["']?\)/).pop();
        if (imageUrlFind) {
            $(".listing-medium.modern .img-holder").addClass("image-loaded");
        }
    }


    // listing grid
    propertGridEqual = new equalHeight(".listing-grid");
    propertGridEqual.equalHeightActive();
    // listing grid

    // listing grid Masnory
    propertGridMasnory = new equalHeight(".masnory .listing-grid");
    propertGridMasnory.equalHeightDisable();
    // listing grid Masnory

    // listing medium modern
    propertMediumModernEqual = new equalHeight(".listing-medium.modern .text-holder");
    propertMediumModernEqual.equalHeightActive();
    // listing medium modern

    // listing-medium Advance
    propertMediumAdvanceEqual = new equalHeight(".listing-medium.advance-grid .text-holder");
    propertMediumAdvanceEqual.equalHeightActive();
    // listing-medium Advance

    // listing-grid Advance
    propertAdvanceEqual = new equalHeight(".listing-grid.advance-grid");
    propertAdvanceEqual.equalHeightDisable();
    propertAdvanceEqual.equalHeightActiveSubChild(".text-holder");
    // listing-grid Advance

    // listing-grid Modern
    propertModernEqual = new equalHeight(".listing-grid.modern");
    propertModernEqual.equalHeightDisable();
    propertModernEqual.equalHeightActiveSubChild(".text-holder");
    // listing-grid Modern

    // listing-grid Modern
    propertModernv1Equal = new equalHeight(".listing-grid.modern.v1");
    propertModernv1Equal.equalHeightActiveSubChild(".post-listing-footer");
    // listing-grid Modern

    // listing-grid default
    propertDefaultEqual = new equalHeight(".listing-grid.default");
    propertDefaultEqual.equalHeightDisable();
    propertDefaultEqual.equalHeightActiveSubChild(".text-holder");
    // listing-grid default

    // blog post grid
    blogGridEqual = new equalHeight(".blog.blog-grid .blog-post");
    blogGridEqual.equalHeightActive();
    // blog post grid

    // member-grid 
    memberGridEqual = new equalHeight(".member-grid .post-inner-member");
    memberGridEqual.equalHeightActive();
    // member-grid 

    // member-grid member-info
    memberInfoEqual = new equalHeight(".member-grid .member-info");
    memberInfoEqual.equalHeightActive();
    // member-grid member-info

    // top-locations
    topLocationsEqual = new equalHeight(".top-locations ul li .image-holder");
    topLocationsEqual.equalHeightActive();
    // top-locations 

    // listing-grid default
    topLocationsEqual = new equalHeight(".listing-grid.default .text-holder");
    topLocationsEqual.equalHeightActive();
    // listing-grid default 

    // Dsidx Listings
    dsidxListings = new equalHeight("#dsidx-listings .dsidx-listing .dsidx-data");
    dsidxListings.equalHeightActive();
    // Dsidx Listings  

    // add class when image loaded
    $(".listing-medium .img-holder img, .listing-grid .img-holder img").one("load", function () {
        $(this).parents(".img-holder").addClass("image-loaded");
    }).each(function () {
        if (this.complete)
            $(this).load();
    });


    function wp_dp_getParameterByName(name, url) {
        if (!url)
            url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results)
            return null;
        if (!results[2])
            return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    /*
     * My listing gear con setting function
     */

    jQuery(document).on("click", ".user-listing .user-list .listing-option-dropdown ul li", function () {
        "use strict";
        var user_list_menu = $('.user-listing .user-list .listing-option-dropdown ul li');
        var current_object = jQuery(this);
        if (!current_object.hasClass('option-open')) {
            current_object.addClass('option-open');
            $(document).one('click', function closeTooltip(e) {
                if (current_object.has(e.target).length === 0 && $('.user-listing .user-list .listing-option-dropdown ul li').has(e.target).length === 0) {
                    current_object.removeClass('option-open');
                } else if (current_object.hasClass('option-open')) {
                    $(document).one('click', closeTooltip);
                }
            });
        } else {
            user_list_menu.removeClass('option-open');
        }
    });


    /*                          
     * Load Dashboard Tabs  
     */
    jQuery(document).on("click", ".user_dashboard_ajax", function () {
        "use strict";

        var _this = jQuery(this);

        jQuery(".wp-dp-button-loader").remove();
        var actionString = jQuery(this).attr("id");
        jQuery("#" + actionString + " a").append('<div class="wp-dp-button-loader spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');



        if (typeof actionString === "undefined") {
            actionString = jQuery(this).attr("data-id");
        }
        jQuery('.user-account-holder .user-holder').removeClass('fadeInUp');
        var pageNum = jQuery(this).attr("data-pagenum");
        var data_param = jQuery(this).attr("data-param");
        var data_sort = jQuery(this).attr("data-sort");
        var data_type = jQuery(this).attr("data-type");

        if (typeof data_param !== "undefined" && data_param !== "") {
            actionString = actionString.replace('_' + data_param, '');
        }

        var filter_parameters = "";
        if (typeof pageNum !== "undefined" || typeof data_param !== "undefined" || typeof data_sort !== "undefined" || typeof data_type !== "undefined") {
            filter_parameters = wp_dp_get_filter_parameters(this);
        } else {
            filter_parameters = "";
        }

        var lang_code_param = '';
        if (typeof lang_code !== "undefined" && lang_code !== '') {
            lang_code_param = "lang=" + lang_code;
        }

        var lang = wp_dp_getParameterByName('lang');
        if (typeof lang !== "undefined" && lang !== '' && lang !== null) {
            lang_code_param = "lang=" + lang;
        }

        var page_qry_append = "";
        if (typeof pageNum === "undefined") {
            if (typeof page_id_all !== "undefined" && page_id_all > 1) {
                pageNum = page_id_all;
                page_qry_append = "&page_id_all=" + page_id_all;
                page_id_all = 0;
            }
        }
        if (typeof pageNum === "undefined" || pageNum == "") {
            pageNum = "1";
        }

        if (typeof data_param !== "undefined" && data_param != '' && data_param != null) {
            page_qry_append += "&data_param=" + data_param;
        }
        if (typeof data_sort !== "undefined" && data_sort != '' && data_sort != null) {
            page_qry_append = page_qry_append + "&data_sort=" + data_sort;
        }
        if (typeof data_type !== "undefined" && data_type != '' && data_type != null) {
            page_qry_append += "&data_type=" + data_type;
        }

        if (typeof _this.attr('data-listing-id') !== 'undefined' && _this.attr('data-listing-id') != '') {
            page_qry_append += "&listing_id=" + _this.attr('data-listing-id');
        }

        if (typeof _this.attr('data-package-id') !== 'undefined' && _this.attr('data-package-id') != '') {
            page_qry_append += "&package_id=" + _this.attr('data-package-id');
        }

        var actionClass = jQuery(this).attr("class");
        var query_var = jQuery(this).data("queryvar");
        if (history.pushState) {
            if (query_var != undefined) {
                if (query_var != "") {
                    if (typeof lang_code_param !== "undefined" && lang_code_param !== '') {
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + lang_code_param + '&' + query_var + page_qry_append;
                    } else {
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + query_var + page_qry_append;
                    }
                } else {
                    if (typeof lang_code_param !== "undefined" && lang_code_param !== '') {
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + lang_code_param;
                    } else {
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    }
                }
                window.history.pushState({
                    path: newurl
                }, "", newurl);
            }
        }

        jQuery(".user_dashboard_ajax").removeClass("active");
        jQuery(".orders-inquiries").removeClass("active");
        wp_dp_show_loader("#wp_dp_member_suggested");
        wp_dp_show_loader(".user-account-holder.loader-holder.dashboard-loader");



        jQuery("#" + actionString + "." + actionClass).addClass("active");
        if (actionString == "wp_dp_member_received_orders" || actionString == "wp_dp_member_received_inquiries") {
            jQuery(".dashboard-nav .orders-inquiries").addClass("active");
            jQuery(".dashboard-nav .orders-inquiries #" + actionString + "." + actionClass).addClass("active");
        } else if (actionString == "wp_dp_member_orders" || actionString == "wp_dp_member_inquiries") {
            jQuery(".dashboard-nav .orders-inquiries").addClass("active");
        }

        if (typeof ajaxRequest != "undefined") {
            ajaxRequest.abort();
        }

        var listin_id = '';
        if (typeof _this.attr('data-listing-id') !== 'undefined' && _this.attr('data-listing-id') != '') {
            listin_id = _this.attr('data-listing-id');
            _this.removeAttr('data-listing-id');
        }

        var package_id = '';
        if (typeof _this.attr('data-package-id') !== 'undefined' && _this.attr('data-package-id') != '') {
            package_id = _this.attr('data-package-id');
            _this.removeAttr('data-package-id');
            package_id = '&package_id=' + package_id;
        }

        ajaxRequest = jQuery.ajax({
            type: "POST",
            url: wp_dp_globals.ajax_url,
            data: "page_id_all=" + pageNum + "&listing_id=" + listin_id + package_id + "&action=" + actionString + filter_parameters,
            success: function (response) {

                //console.log(response);
                if (actionString == 'wp_dp_member_accounts') {
                    jQuery('.user-account-holder').addClass('profile-settings');
                } else {
                    jQuery('.user-account-holder').removeClass('profile-settings');
                }

                if (actionString == 'wp_dp_member_suggested' || actionString == 'wp_dp_member_listings' || actionString == 'wp_dp_member_published_listings' || actionString == 'wp_dp_member_pending_listings' || actionString == 'wp_dp_member_expired_listings') {
                    jQuery('.user-listings-statics').show();
                } else {
                    jQuery('.user-listings-statics').hide();
                }
                wp_dp_hide_loader();
                var timesRun = 0;
                setInterval(function () {
                    timesRun++;
                    if (timesRun === 1) {
                        if (jQuery(document).find("#cropContainerModal").attr("data-img-type") == "default") {
                            jQuery("#cropContainerModal .cropControls").hide();
                        }
                    }
                }, 50);

                jQuery(".user-holder").html(response);
                /*
                 * dashboard animation start
                 */


                jQuery('.user-account-holder .user-holder').addClass('fadeInUp');

                jQuery("#" + actionString + " a .wp-dp-button-loader").remove();

                /*
                 setTimeout(function () {
                 jQuery('.user-account-holder .user-holder').addClass('fadeOutDown');
                 }, 1000);
                 */


                if (jQuery(".user-account-holder .user-holder").length > 0) {
                    //jQuery('.user-account-holder .user-holder').removeClass('fadeOutDown');
                    //jQuery('.user-account-holder .user-holder').removeClass('hidden');
                    //jQuery('.user-account-holder .user-holder').addClass('fadeInUp');
                }

                /*
                 * dashboard animation end
                 */



                chosen_selectionbox();

            }
        });
    });

    /*
     * Saving Member Data
     */
    jQuery(document).on("click", "#company_profile_form", function () {
        "use strict";
        wp_dp_show_loader();
        var serializedValues = jQuery("#member_company_profile").serialize();
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: wp_dp_globals.ajax_url,
            data: serializedValues + "&action=wp_dp_save_company_data",
            success: function (response) {
                wp_dp_show_response(response);
            }
        });
    });
});
/*
 * register pop up
 */

jQuery(document).on("click", ".no-logged-in", function () {
    $("#join-us").modal();
});

/* range slider */

jQuery(document).ready(function () {

    /*Featured Slider Start*/

    if ("" != jQuery(".featured-slider .swiper-container").length) {
        new Swiper(".featured-slider .swiper-container", {
            nextButton: ".swiper-button-next",
            prevButton: ".swiper-button-prev",
            paginationClickable: true,
            slidesPerView: 1,
            slidesPerColumn: 1,
            grabCursor: !0,
            loop: !0,
            spaceBetween: 30,
            arrow: false,
            pagination: ".swiper-pagination",
            breakpoints: {
                1024: {
                    slidesPerView: 1
                },
            }
        })
    }

});

/**
 * show alert message
 */

function show_alert_msg(msg) {
    "use strict";
    jQuery("#member-dashboard .main-cs-loader").html("");
    jQuery(".cs_alerts").html('<div class="cs-dpove-msg"><i class="icon-check-circle"></i>' + msg + "</div>");
    var classes = jQuery(".cs_alerts").attr("class");
    classes = classes + " active";
    jQuery(".cs_alerts").addClass(classes);
    setTimeout(function () {
        jQuery(".cs_alerts").removeClass("active");
    }, 4e3);
}

//jQuery(window).load(function() {
//	if (jQuery(".user-account-holder .user-holder").length > 0) {
//   		jQuery('.user-account-holder .user-holder').addClass('fadeInUp');		
//	}
//});


/*HTML Functions Start*/
jQuery(document).ready(function () {



    if (jQuery(".opening-hours-block > ul > li").length > 0) {
        jQuery(document).on("click", ".opening-hours-block > ul > li", function () {
            "use strict";
            var user_list_menu = $('.opening-hours-block > ul > li');
            var current_object = jQuery(this);
            if (!current_object.hasClass('hours-menu-open')) {
                current_object.addClass('hours-menu-open');
                $(document).one('click', function closeTooltip(e) {
                    current_object.removeClass('hours-menu-open');
                    if (current_object.has(e.target).length === 0 && $('.opening-hours-block > ul > li').has(e.target).length === 0) {
                        current_object.removeClass('hours-menu-open');
                    } else if (current_object.hasClass('hours-menu-open')) {
                        $(document).one('click', closeTooltip);
                    }
                });
            } else {
                user_list_menu.removeClass('hours-menu-open');
            }
        });
    }


    /*jQuery('.modern-filters li span a[data-toggle="tab"]').on('shown.bs.tab', function(e){
     alert("activeTab");
     mySlider.update();
     });*/


    if (jQuery(".user-account-holder .user-holder").length > 0) {
        jQuery('.user-account-holder .user-holder').addClass('fadeInUp');
    }

    /*Split Map Fixed Filters Function Start*/
    function split_map_fixed_filters() {
        var split_map_filters_height = $(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder .split-map-fixed-filter").height();
        var split_map_container_width = $(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder").outerWidth();
        jQuery(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder").css({
            "min-height": split_map_filters_height
        });
        jQuery(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder .split-map-fixed-filter").css({
            "width": split_map_container_width
        });
        jQuery(".wp-dp-split-map-wrap .split-map-container .split-search-btn-holder").css({
            "width": split_map_container_width
        });

    }
    if (jQuery(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder .split-map-fixed-filter").length > 0) {
        jQuery(".main-search.split-map .dropdown-with-btn .field-holder.more-filters-btn a").click(function () {
            //jQuery(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder .split-map-fixed-filter").toggleClass("filter-fixed");
        });
        split_map_fixed_filters();
        jQuery(window).resize(function () {
            split_map_fixed_filters();
        });
    }
    /*Split Map Fixed Filters Function End*/
    if (jQuery(".main-header .field-holder.search-input.with-search-country .search-listing-field").length > 0) {
        jQuery(document).on('click', '.main-header .field-holder.search-input.with-search-country .search-listing-field', function (event) {
            jQuery(".main-header .field-holder.search-input.with-search-country .search-country").removeClass("expand-form");
            event.preventDefault();
            event.stopPropagation();
            jQuery(".main-header .field-holder.search-input.with-search-country .search-listing-field").addClass('expand-form');
        });
        jQuery(document).click(function (e) {
            if (jQuery(".main-header .field-holder.search-input.with-search-country .search-listing-field.expand-form").length > 0) {
                jQuery(".main-header .field-holder.search-input.with-search-country .search-listing-field").removeClass("expand-form");
            }
        });
        jQuery(document).on('click', '.listing-autocomplete-result', function (event) {
            event.preventDefault();
            event.stopPropagation();
            jQuery(".main-header .field-holder.search-input.with-search-country .search-listing-field").removeClass("expand-form");
        });
    }
    if (jQuery(".main-header .field-holder.search-input.with-search-country .search-country").length > 0) {
        jQuery(document).on('click', '.main-header .field-holder.search-input.with-search-country .search-country', function (event) {
            jQuery(".main-header .field-holder.search-input.with-search-country .search-listing-field").removeClass("expand-form");
            event.preventDefault();
            event.stopPropagation();
            jQuery(".main-header .field-holder.search-input.with-search-country .search-country").addClass('expand-form');
        });
        jQuery(document).click(function (e) {
            if (jQuery(".main-header .field-holder.search-input.with-search-country .search-country.expand-form").length > 0) {
                jQuery(".main-header .field-holder.search-input.with-search-country .search-country").removeClass("expand-form");
            }
        });
        jQuery(document).on('click', '.main-header .wp_dp_location_autocomplete', function (event) {
            event.preventDefault();
            event.stopPropagation();
            jQuery(".main-header .field-holder.search-input.with-search-country .search-country").removeClass("expand-form");
        });
    }

    /*Split Map Overlay Functions Start*/
    //	if (jQuery(".main-search.split-map .field-holder.more-filters-btn").length > 0) {	
    //		function splitMapOverlay() {
    //			var NewContent='<div class="split-map-overlay"></div>';
    //			$(".main-search.split-map .field-holder.more-filters-btn").click(function(){
    //                            if (NewContent != '') {
    //                                    $(".wp-dp-top-map-holder").after(NewContent);
    //                                    NewContent = '';
    //                            } else {
    //                                    $('.wp-dp-top-map-holder').next().toggle();
    //                            }
    //			});	
    //		}	
    //		splitMapOverlay();
    //		$(document).ajaxComplete(function(){
    //			splitMapOverlay();
    //		});
    //		$(window).resize(function () {
    //			splitMapOverlay();
    //		});	
    //	}
    /*Split Map Overlay Functions End*/
    if (jQuery(".main-search.split-map .search-advanced-fields").length > 0) {
        function SearchAdvancedFields() {
            var WindowHeightForAdvancedFields = $(window).height();
            $(".main-search.split-map .search-advanced-fields").css({
                "max-height": WindowHeightForAdvancedFields - 340,
                "overflow-y": "auto"
            });
        }
        SearchAdvancedFields();
        $(document).ajaxComplete(function () {
            SearchAdvancedFields();
        });
        $(window).resize(function () {
            SearchAdvancedFields();
        });
    }

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
    }
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
        }
    });

    function dashboard_fixed_heder() {
        if (jQuery(".dashboard-fixed-header").length > 0) {
            var get_dashboard_height = $("#header .dashboard-fixed-header").innerHeight();
            var get_dashboard_adminbar = 0;
            if ($("#wpadminbar").length) {
                get_dashboard_adminbar = $("#wpadminbar").height();
            }
            $("#header").css({
                "min-height": get_dashboard_height
            });
            $(".dashboard-fixed-header").css({
                "top": get_dashboard_adminbar
            });
            $(".wp-dp .dashboard-sidebar-panel").css({
                "padding-top": get_dashboard_height + get_dashboard_adminbar
            });
        }
    }
    dashboard_fixed_heder();
});

jQuery(document).ready(function () {
    jQuery('.user-account-nav.user-account-sidebar ul.dashboard-nav').find('li.nav-open').find('> ul > li').slideToggle('slow');
    if (jQuery(".user-account-nav.user-account-sidebar ul.dashboard-nav li").length != "") {
        jQuery('.user-account-nav.user-account-sidebar ul.dashboard-nav li > a').on('click', function (e) {
            if (jQuery(this).parent().hasClass('user_dashboard_ajax') || jQuery(this).parent().hasClass('accordian')) {
                e.preventDefault();
                if (jQuery(this).parent().find('> ul > li').length > 0) {
                    jQuery(this).parent().toggleClass('nav-open active').find('> ul > li').slideToggle('slow');
                    jQuery(this).parent().siblings().find('> ul > li').slideUp('slow');
                    jQuery(this).parent().siblings().removeClass('nav-open active');
                } else {
                    jQuery(this).parent().siblings().find('> ul > li').slideUp('slow');
                    jQuery(this).parent().siblings().removeClass('nav-open active');
                }
            }
        });
    }


    function SidbarPanelWidth() {
        var HeaderOuterHeightForSidebarPanel = $("#header").height();
        var WPA_dminbarHeightForSidebarPanel = 0;
        if ($("#wpadminbar").length) {
            WPA_dminbarHeightForSidebarPanel = $("#wpadminbar").height();
        }
        $(".wp-dp .dashboard-sidebar-panel").css({
            "padding-top": HeaderOuterHeightForSidebarPanel + WPA_dminbarHeightForSidebarPanel + 30
        });
    }

    SidbarPanelWidth();
    $(window).resize(function () {
        SidbarPanelWidth();
    });

    function SidbarPanelHeight() {
        var WindowHeightForSidbarPanel = $(window).height();
        $(".user-account-nav.user-account-sidebar").css({
            "max-height": WindowHeightForSidbarPanel - 240,
            "overflow-y": "auto"
        });
    }
    SidbarPanelHeight();
    $(window).resize(function () {
        SidbarPanelHeight();
    });

    if ($(".page-section.account-header").width() < 991) {
        if (jQuery('.dashboard-sidebar-panel').length > 0) {
            $('.dashboard-sidebar-panel .dashboard-nav-btn').click(function (e) {
                e.preventDefault();
                if ($('.dashboard-sidebar-panel').hasClass('sidebar-nav-open')) {
                    $('.dashboard-sidebar-panel').removeClass('sidebar-nav-open');
                } else {
                    $('.dashboard-sidebar-panel').addClass('sidebar-nav-open');
                }
            });
        }
    }

    $('.spinner-btn > .form-control').attr("readonly", "readonly");
    // search placeholder remover.
    $(".main-search.advance .search-input input").blur(function () {
        if ($(this).val()) {
            $(this).next().hide();
        } else {
            $(this).next().show();
        }
    });
    // search placeholder remover.
    /*
     * detail page nav listing feature toggler
     */

    $(".detail-nav-toggler").click(function () {
        $(".detail-nav").slideToggle().toggleClass("open");
    });
    //    $(".detail-nav-toggler").click(function () {
    //        $(this).next(".detail-nav").slideToggle().toggleClass("open");
    //    });
    /*Detail Nav Sticky*/

    function stickyDetailNavBar() {
        "use strict";
        var $window = $(window);
        if ($window.width() > 980) {
            if ($(".detail-nav").length) {
                var el = $(".detail-nav");
                var stickyTopp = $(".detail-nav").offset().top;
                var stickyHeight = $(".detail-nav").height();
                var AdminBarHeight_ = $("#wpadminbar").height();
                if ($("#wpadminbar").length > 0) {
                    stickyTopp = stickyTopp - AdminBarHeight_;
                }
                $(window).scroll(function () {
                    var windowTop = $(window).scrollTop();
                    if (stickyTopp < windowTop) {
                        el.css({
                            position: "fixed",
                            width: "100%",
                            "z-index": "1000",
                            top: "0"
                        });
                        $(".detail-nav").css("margin-top", AdminBarHeight_);
                        $(".listing-detail").css("padding-top", stickyHeight);
                        $(".detail-nav-wrap.detail-v5 .detail-nav").addClass("detail-nav-sticky");
                    } else {
                        el.css({
                            position: "relative",
                            width: "100%",
                            "z-index": "initial",
                            top: "auto"
                        });
                        $(".detail-nav").css("margin-top", "0");
                        $(".listing-detail").css("padding-top", "0");
                        $(".detail-nav-wrap.detail-v5 .detail-nav").removeClass("detail-nav-sticky");
                    }
                });
            }
        }
    }
    stickyDetailNavBar();
    $(window).resize(function () {
        stickyDetailNavBar();
    });

    /*Scroll Nav and Active li Start*/
    if (jQuery(".detail-nav-map").length != "" && jQuery(".listing-act-btns-list").length === 0) {
        var wpadminbarHeight = 0;
        if ($("#wpadminbar").length) {
            wpadminbarHeight = $("#wpadminbar").height();
        }
        var lastId, topMenu = $(".detail-nav-map"),
            topMenuHeight = topMenu.outerHeight() + 15 + wpadminbarHeight,
            menuItems = topMenu.find("ul li a"),
            scrollItems = menuItems.map(function () {
                var item = $($(this).attr("href"));
                if (item.length) {
                    return item;
                }
            });

        menuItems.click(function (e) {
            var href = $(this).attr("href"),
                offsetTop = href === "#" ? 0 : $(href).offset().top - topMenuHeight + 1;
            $("html, body").stop().animate({
                scrollTop: offsetTop
            }, 650);
            e.preventDefault();
        });

        $(window).scroll(function () {
            var fromTop = $(this).scrollTop() + topMenuHeight;
            var cur = scrollItems.map(function () {
                if ($(this).offset().top < fromTop)
                    return this;
            });
            cur = cur[cur.length - 1];
            var id = cur && cur.length ? cur[0].id : "";
            if (lastId !== id) {
                lastId = id;
                menuItems.parent().removeClass("active").end().filter("[href='#" + id + "']").parent().addClass("active");
            }
        });
    }

    /*Detail Nav Sticky*/

    /*Modal Backdrop Start*/

    jQuery(".main-search .search-popup-btn").click(function () {
        setTimeout(function () {
            jQuery(".modal-backdrop").appendTo(".main-search.fancy");
        }, 4);
    });
    jQuery(".detail-nav-map .enquire-holder a").click(function () {
        setTimeout(function () {
            jQuery(".modal-backdrop").appendTo(".detail-nav");
        }, 4);
    });
    jQuery(".detail-v5 .detail-nav ul li a").click(function () {
        setTimeout(function () {
            jQuery(".modal-backdrop").appendTo(".detail-nav");
        }, 4);
    });
    jQuery(".profile-info.boxed .submit-btn").click(function () {
        setTimeout(function () {
            jQuery(".modal-backdrop").appendTo(".detail-nav");
        }, 4);
    });

    /*               
     * listing banner slider start
     */




    if (jQuery(".banner .listing-banner-slider .swiper-container").length != "") {
        var mySwiper = new Swiper(".banner .listing-banner-slider .swiper-container", {
            pagination: ".swiper-pagination",
            paginationClickable: true,
            loop: false,
            grabCursor: true,
            nextButton: ".banner .listing-banner-slider .swiper-button-next",
            prevButton: ".banner .listing-banner-slider .swiper-button-prev",
            spaceBetween: 30,
            autoplay: 3e3,
            effect: "fade",
            onInit: function (swiper) {
                stickyDetailNavBar();
            }
        });
    }

    /*===========Range Slider Start============
     ==========================================*/
    if ($(".range-slider").length > 0) {
        // Instantiate listing price slider
        var ppValue = $(".range-slider #ex2").bootstrapSlider();
        ppValue.bootstrapSlider().on('change', function (event) {
            var a = event.value.newValue;
            $(this).parents(".range-slider").find(".slider-value").text(a);
        });
        // Instantiate listing price slider

        // Instantiate Deposit price slider
        var depValue = $(".range-slider #ex3").bootstrapSlider();
        depValue.bootstrapSlider().on('change', function (event) {
            var a = event.value.newValue;
            $(this).parents(".range-slider").find(".slider-value").text(a);
        });
        // Instantiate Deposit price slider

        // Instantiate Annual Interest price slider
        var anlValue = $(".range-slider #ex4").bootstrapSlider();
        anlValue.bootstrapSlider().on('change', function (event) {
            var a = event.value.newValue;
            $(this).parents(".range-slider").find(".slider-value").text(a);
        });
        // Instantiate Annual Interest price slider

        // Instantiate Year value slider
        var yearValue = $(".range-slider #ex5").bootstrapSlider();
        yearValue.bootstrapSlider().on('change', function (event) {
            var a = event.value.newValue;
            $(this).parents(".range-slider").find(".slider-value").text(a);
        });
        // Instantiate Year value slider
    }
    /*===========Range Slider Start============
     ==========================================*/


    /*Main Categories List Show Hide*/

    if (jQuery(".categories-holder .text-holder ul").length != "" && jQuery(".categories-holder .text-holder ul").data("showmore") == "yes") {
        jQuery(".categories-holder .text-holder ul").each(function () {
            var $ul = $(this),
                $lis = $ul.find("li:gt(3)"),
                isExpanded = $ul.hasClass("expanded");
            $lis[isExpanded ? "show" : "hide"]();
            if ($lis.length > 0) {
                $ul.append($('<li class="expand">' + (isExpanded ? "Less" : "view More") + "</li>").click(function (event) {
                    var isExpanded = $ul.hasClass("expanded");
                    event.preventDefault();
                    $(this).text(isExpanded ? "view More" : "Less");
                    $ul.toggleClass("expanded");
                    $lis.toggle(350);
                }));
            }
        });
    }

    /*Modal Tab Link Start*/

    if (jQuery(".login-popup-btn").length != "") {
        jQuery(".login-popup-btn").click(function (e) {
            jQuery(".cs-login-switch").click();
            var tab = e.target.hash;
            var data_id = jQuery(this).data("id");
            jQuery(".tab-content .popupdiv" + data_id).removeClass("in active");
            jQuery('a[href="' + tab + '"]').tab("show");
            jQuery(tab).addClass("in active");
        });
    }

    /*Modal Tab Link End*/

    $(document).on("click", ".reviews-sortby li.reviews-sortby-active", function () {
        setTimeout(function () {
            jQuery("#reviews-overlay").remove();
        }, 4);
    });

    jQuery(".reviews-sortby > li").on("click", function () {
        jQuery("#reviews-overlay").remove();
        setTimeout(function () {
            jQuery(".reviews-sortby > li").toggleClass("reviews-sortby-active");
        }, 3);
        jQuery(".reviews-sortby > li").siblings();
        jQuery(".reviews-sortby > li").siblings().removeClass("reviews-sortby-active");
        jQuery(".reviews-sortby").append("<div id='reviews-overlay' class='reviews-overlay'></div>");
    });

    jQuery(".input-reviews > .radio-field label").on("click", function () {
        jQuery(this).parent().toggleClass("active");
        jQuery(this).parent().siblings();
        jQuery(this).parent().siblings().removeClass("active");
        /*replace inner Html*/
        var radio_field_active = jQuery(this).html();
        jQuery(".active-sort").html(radio_field_active);
        jQuery(".reviews-sortby > li").removeClass("reviews-sortby-active");
        setTimeout(function () {
            jQuery("#reviews-overlay").remove();
        }, 400);
    });

    $(document).on("click", "#reviews-overlay", function () {
        "use strict";
        jQuery(this).closest(".reviews-overlay").remove();
        jQuery(".reviews-sortby > li").removeClass("reviews-sortby-active");
    });

    /* Spinner Btn Start*/

    $(".spinner .btn:last-of-type").on("click", function () {
        $(".spinner input").val(parseInt($(".spinner input").val(), 10) + 1);
    });

    $(".spinner .btn:first-of-type").on("click", function () {
        var val = parseInt($(".spinner input").val(), 10);
        if (val < 1) {
            return;
        }
        $(".spinner input").val(val - 1);
    });

    $(".spinner2 .btn:last-of-type").on("click", function () {
        $(".spinner2 input").val(parseInt($(".spinner2 input").val(), 10) + 1);
    });

    $(".spinner2 .btn:first-of-type").on("click", function () {
        $(".spinner2 input").val(parseInt($(".spinner2 input").val(), 10) - 1);
    });

    $(".spinner3 .btn:last-of-type").on("click", function () {
        $(".spinner3 input").val(parseInt($(".spinner3 input").val(), 10) + 1);
    });

    $(".spinner3 .btn:first-of-type").on("click", function () {
        $(".spinner3 input").val(parseInt($(".spinner3 input").val(), 10) - 1);
    });

    /* Spinner Btn End*/


    jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").on("click", function (e) {
        e.preventDefault();
        jQuery(this).parent().toggleClass("menu-open");
        jQuery(this).parent().siblings().removeClass("menu-open");
        setTimeout(function () {
            jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").addClass("open-overlay");
        }, 2);
        jQuery(".main-header .login-option,.main-header .login-area").append("<div class='location-overlay'></div>");
        jQuery(".user-dashboard-menu > ul > li > ul").append("<i class='icon-cross close-menu-location'></i>");
    });

    jQuery(document).on("click", ".user-dashboard-menu > ul > li.user-dashboard-menu-children > a.open-overlay", function () {
        jQuery(".location-overlay").remove();
        jQuery(".close-menu-location").remove();
        setTimeout(function () {
            jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").removeClass("open-overlay");
        }, 2);
    });

    $(".main-header .user-dashboard-menu li.user-dashboard-menu-children ul").bind("clickoutside", function (event) {
        $(this).hide();
    });

    jQuery(document).on("click", ".location-overlay", function () {
        "use strict";
        jQuery(this).closest(".location-overlay").remove();
        jQuery(".close-menu-location").remove();
        jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children").removeClass("menu-open");
        jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").removeClass("open-overlay");
    });

    jQuery(document).on("click", ".close-menu-location", function () {
        jQuery(this).closest(".close-menu-location").remove();
        jQuery(".location-overlay").remove();
        jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children").removeClass("menu-open");
        jQuery(".user-dashboard-menu > ul > li.user-dashboard-menu-children > a").removeClass("open-overlay");
    });

    /*cs-calendar-combo input Start*/
    jQuery(document).ready(function () {
        if (jQuery(".cs-calendar-from input").length != "") {
            jQuery(".cs-calendar-from input").datetimepicker({
                timepicker: false,
                format: "Y/m/d",
            });
        }

        if (jQuery(".cs-calendar-to input").length != "") {
            jQuery(".cs-calendar-to input").datetimepicker({
                timepicker: false,
                format: "Y/m/d",
            });
        }
    });
    /*Flickr Gallery Slider Functions Start*/



    if (jQuery(".flickr-gallery-slider .swiper-container").length != '') {



        var swiper = new Swiper('.flickr-gallery-slider .swiper-container', {
            nextButton: '.flickr-gallery-slider .swiper-button-next',
            prevButton: '.flickr-gallery-slider .swiper-button-prev',
            paginationClickable: true,
            spaceBetween: 0,
            centeredSlides: true,
            autoplay: 2500,
            autoplayDisableOnInteraction: false,
            loop: false,
        });

    }

    /*Flickr Gallery Slider Functions End*/
    /*prettyPhoto Start*/

    if (jQuery(".photo-gallery.gallery").length != "") {
        jQuery("area[data-rel^='prettyPhoto']").prettyPhoto();
        jQuery(".gallery:first a[data-rel^='prettyPhoto']").prettyPhoto({
            animation_speed: "normal",
            theme: "light_square",
            slideshow: 5e3,
            deeplinking: true,
            autoplay_slideshow: true
        });

        jQuery(".gallery:gt(0) a[data-rel^='prettyPhoto']").prettyPhoto({
            animation_speed: "fast",
            slideshow: 5e4,
            deeplinking: false,
            hideflash: true
        });

        jQuery("#custom_content a[data-rel^='prettyPhoto']:first").prettyPhoto({
            custom_markup: '<div id="map_canvas"></div>',
            changepicturecallback: function () {
                initialize();
            }
        });

        jQuery("#custom_content a[data-rel^='prettyPhoto']:last").prettyPhoto({
            custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
            changepicturecallback: function () {
                _bsap.exec();
            }
        });
    }

    /*prettyPhoto End*/

    /* Gallery Counter Start*/

    if (jQuery(".photo-gallery .gallery-counter li").length != "") {
        count = jQuery(".photo-gallery .gallery-counter li").size();
        if (count > 7) {
            jQuery(".photo-gallery .gallery-counter  li:gt(6) .img-holder figure").append("<figcaption><span></span></figcaption>");
            jQuery(".photo-gallery .gallery-counter  li figure figcaption span").append('<em class="counter"></em>');
            jQuery(".photo-gallery .gallery-counter  li figure figcaption span .counter").html("<i class='icon-plus'></i>" + count);
        } else {
            jQuery('<em class="counter"></em>').remove();
        }
        jQuery(".photo-gallery .gallery-counter  li:gt(7)").hide();
    }

});

/*
 * Framework JS
 */
jQuery(document).on("click", ".icon-circle-with-cross", function () {
    "use strict";
    jQuery(this).parents("li").remove();
    var attachment_id = jQuery(this).attr("data-attachment_id");
    var all_attachments = jQuery("#wp_dp_member_gallery_attathcments").val();
    var new_attachemnts = all_attachments.replace(attachment_id, "");
    jQuery("#wp_dp_member_gallery_attathcments").val(new_attachemnts);
});

var size_li = jQuery("#collapseseven .cs-checkbox-list li").size();
x = 5;
jQuery("#collapseseven .cs-checkbox-list li:lt(" + x + ")").show(200);

jQuery(document).on("click", ".reset-results", function () {
    "use strict";
    jQuery(".search-results").fadeOut(200);
});

jQuery(document).on("click", "#pop-close1", function () {
    "use strict";
    jQuery("#popup1").addClass("popup-open");
});

jQuery(document).on("click", "#close1", function () {
    "use strict";
    jQuery("#popup1").removeClass("popup-open");
});

jQuery(document).on("click", "#pop-close", function () {
    "use strict";
    jQuery("#popup").addClass("popup-open");
});

jQuery(document).on("click", "#close", function () {
    "use strict";
    jQuery("#popup").removeClass("popup-open");
});

if (jQuery(".selectpicker").length != "") {
    jQuery(".selectpicker").selectpicker({
        size: 5
    });
}

jQuery(".closeall").click(function () {
    jQuery(".openall").addClass("show");
    jQuery(".filters-options .panel-collapse.in").collapse("hide");
});

jQuery(".openall").click(function () {
    jQuery(".openall").removeClass("show");
    jQuery('.filters-options .panel-collapse:not(".in")').collapse("show");
});

jQuery(".orders-list li a.orders-detail").on("click", function (e) {
    "use strict";
    e.preventDefault();
    jQuery(this).parent().addClass("open").find(".orders-list .info-holder");
    jQuery(this).parent().siblings().find(".orders-list .info-holder");
});

jQuery(".orders-list li a.close").on("click", function (e) {
    e.preventDefault();
    jQuery(".orders-list > li.open").removeClass("open");
});

/* On Scroll Fixed Map Start*/

if (jQuery(".listing-map-holder.map-right .detail-map").length != "") {
    "use strict";
    var Header_height = jQuery("header#header").height();
    if (jQuery(".listing-map-holder.map-right .detail-map").length != "") {
        jQuery("header#header").addClass("fixed-header");
        jQuery(".listing-map-holder.map-right .detail-map").addClass("fixed-item").css("padding-top", Header_height);
    } else {
        jQuery(".listing-map-holder.map-right .detail-map").removeClass("fixed-item").css("padding-top", "auto");
        jQuery("header#header").removeClass("fixed-header");
    }
}

/* Close Effects Start */

jQuery(".clickable").on("click", function () {
    "use strict";
    var effect = jQuery(this).data("effect");
    jQuery(this).closest(".page-sidebar")[effect]();
});

jQuery(".filter-show").on("click", function () {
    jQuery(".page-sidebar").fadeIn();
});

/*
 * Croppic Block
 */

jQuery(document).on("click", ".cropControls .cropControlRemoveCroppedImage", function () {
    "use strict";
    jQuery("#cropContainerModal .cropControls").hide();
    var img_src = jQuery("#cropContainerModal").attr("data-def-img");
    var timesRun = 0;
    setInterval(function () {
        timesRun++;
        if (timesRun === 1) {
            jQuery("#cropContainerModal").find("figure a img").attr("src", img_src);
        }
    }, 50);
});

jQuery(document).on("click", ".upload-file", function () {
    jQuery(".cropControlUpload").click();
});

jQuery(document).on("click", ".cropControlRemoveCroppedImage", function () {
    "use strict";
    jQuery("#cropContainerModal img").attr("src", "");
    jQuery("#wp_dp_member_profile_image").val("");
});

/*
 * Location Block
 */

jQuery(document).on("click", ".loc-icon-holder", function () {
    "use strict";
    var thisObj = jQuery(this);
    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function (position) {

            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var dataString = "lat=" + pos.lat + "&lng=" + pos.lng + "&action=wp_dp_get_geolocation";
            jQuery.ajax({
                type: "POST",
                url: wp_dp_globals.ajax_url,
                data: dataString,
                dataType: "json",
                success: function (response) {
                    thisObj.next("input").val(response.address);
                }
            });
        });
    }
});

/*
 * Opening Hours Block
 */

/*Delivery Timing Dropdown Functions Start*/

jQuery(document).ready(function ($) {
    $(".field-select-holder .active").on("click", function () {
        "use strict";
        $(this).next("ul").slideToggle();
        $(this).parents("ul").toggleClass("open");
        $(".dropdown-select > li > a").on("click", function (e) {
            e.preventDefault();
            var anchorText = $(this).text();
            $(".field-select-holder .active small").text(anchorText);
            $(".field-select-holder .active").next("ul").slideUp();
            $(this).parents("ul").removeClass("open");
        });
    });

    $(document).mouseup(function (e) {
        var container = $(".field-select-holder > ul");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $(".field-select-holder .active").next("ul").slideUp();
            $(".field-select-holder > ul").removeClass("open");
        }
    });

    $(".field-select-holder ul li ul.delivery-dropdown li").click(function () {
        $(".field-select-holder .active").next("ul").slideUp();
        $(".field-select-holder > ul").removeClass("open");
    });

    jQuery(document).on("click", "#member-opening-hours-btn", function () {
        "use strict";
        var thisObj = jQuery(this);
        wp_dp_show_loader("#member-opening-hours-btn", "", "button_loader", thisObj);
        var serializedValues = jQuery("#member-opening-hours-form").serialize();
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: wp_dp_globals.ajax_url,
            data: serializedValues + "&action=wp_dp_member_opening_hours_submission",
            success: function (response) {
                wp_dp_show_response(response, "", thisObj);
            }
        });
    });
});


function wp_dp_top_search(counter) {
    "use strict";
    var thisObj = jQuery(".search-btn-loader-" + counter);
    wp_dp_show_loader(".search-btn-loader-" + counter, "", "button_loader", thisObj);
    jQuery("#top-search-form-" + counter).find("input, textarea, select").each(function (_, inp) {
        if (jQuery(inp).val() === "" || jQuery(inp).val() === null)
            inp.disabled = true;
    });
}

/*
 * chosen selection box
 */

function chosen_selectionbox() {
    "use strict";
    if (jQuery(".chosen-select, .chosen-select-deselect, .chosen-select-no-single, .chosen-select-no-results, .chosen-select-width").length != "") {
        var config = {
            ".chosen-select": {
                width: "100%"
            },
            ".chosen-select-deselect": {
                allow_single_deselect: true
            },
            ".chosen-select-no-single": {
                disable_search_threshold: 10,
                width: "100%"
            },
            ".chosen-select-no-results": {
                no_results_text: "Oops, nothing found!"

            },
            ".chosen-select-width": {
                width: "95%"
            }
        };
        for (var selector in config) {
            jQuery(selector).chosen(config[selector]);
        }
    }
}

// Chosen touch support.
if ($('.chosen-container').length > 0) {
    $('.chosen-container').on('touchstart', function (e) {
        e.stopPropagation();
        e.preventDefault();
        // Trigger the mousedown event.
        $(this).trigger('mousedown');
    });
}

disableClassSelect = ".option-sec .field-holder," +
    ".main-search .select-dropdown";

disableToggleClasses = ".main-search .search-btn," +
    ".main-search .search-input," +
    ".option-sec .field-holder," +
    ".main-search .select-dropdown";

jQuery(document).on('click', disableClassSelect, function (event) {
    event.preventDefault();
    event.stopPropagation();
    jQuery(disableToggleClasses).toggleClass('disable-search');
});

jQuery(document).click(function (e) {
    if (jQuery(".disable-search").length > 0) {
        jQuery(".disable-search").removeClass("disable-search");
    }
    if (jQuery(".disable-select").length > 0) {
        jQuery(".disable-select").removeClass("disable-select");
    }
});

jQuery(document).click(function (e) {
    if (jQuery(".disable-search").length > 0) {
        jQuery(".disable-search").removeClass("disable-search");
    }
});

function wp_dp_multicap_all_functions() {
    "use strict";
    var all_elements = jQuery(".g-recaptcha");
    for (var i = 0; i < all_elements.length; i++) {
        var id = all_elements[i].getAttribute("id");
        var site_key = all_elements[i].getAttribute("data-sitekey");
        if (null != id) {
            grecaptcha.render(id, {
                sitekey: site_key,
                callback: function (resp) {
                    jQuery.data(document.body, "recaptcha", resp);
                }
            });
        }
    }
}

/*
 * captcha reload
 */

function captcha_reload(admin_url, captcha_id) {


    //alert('fs');
    "use strict";
    jQuery("#" + captcha_id + "_div").html('');
    var dataString = "&action=wp_dp_reload_captcha_form&captcha_id=" + captcha_id;
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        dataType: "html",
        success: function (data) {
            jQuery("body").append(data);
        }
    });
}

/*More Less Text Start*/

var showChar = 490;

// How many characters are shown by default

var ellipsestext = "...";
var moretext = "Read more >>";
var lesstext = "Read Less >>";

/* counter more Start */

jQuery(".more").each(function () {
    var content = jQuery(this).text();
    var showcharnew = $(this).attr("data-count");
    if (showcharnew != undefined && showcharnew != "") {
        showChar = showcharnew;
    }

    if (content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);
        var html = c + '<span class="moreellipses">' + ellipsestext + ' </span><span class="morecontent"><span>' + h + '</span>  <a href="" class="readmore-text">' + moretext + "</a></span>";
        jQuery(this).html(html);
    }
});
/*Read More Text Start*/

jQuery(".readmore-text").click(function () {
    "use strict";
    if (jQuery(this).hasClass("less")) {
        jQuery(this).removeClass("less");
        jQuery(this).html(moretext);
    } else {
        jQuery(this).addClass("less");
        jQuery(this).html(lesstext);
    }
    jQuery(this).parent().prev().toggle();
    jQuery(this).prev().toggle();
    return false;
});

/*Upload Gallery Start*/

if (jQuery(".upload-gallery").length != "") {
    function dragStart(ev) {
        ev.dataTransfer.effectAllowed = "move";
        ev.dataTransfer.setData("Text", ev.target.getAttribute("id"));
        ev.dataTransfer.setDragImage(ev.target, 100, 100);
        return true;
    }
}

if (jQuery(".upload-gallery").length != "") {
    function dragEnter(ev) {
        event.preventDefault();
        ev.css({
            margin: "0 0 0 15px"
        });
        return true;
    }
}

if (jQuery(".upload-gallery").length != "") {
    function dragOver(ev) {
        event.preventDefault();
        ev.css({
            margin: "0 0 0 15px"
        });
    }
}

if (jQuery(".upload-gallery").length != "") {
    function dragDrop(ev) {
        var data = ev.dataTransfer.getData("Text");
        ev.target.appendChild(document.getElementById(data));
        ev.stopPropagation();
        return false;
    }
}

if (jQuery(".files").length != "") {
    $(".files").sortable({
        revert: true
    });
}

jQuery(document).ready(function ($) {
    "use strict";
    if ($("body").hasClass("rtl") == true) {
        jQuery('[data-toggle="popover"]').popover({
            placement: 'right'
        });
    } else {
        jQuery('[data-toggle="popover"]').popover();
    }
});


var default_loader = jQuery(".wp_dp_loader").html();
var default_button_loader = jQuery(".wp-dp-button-loader").html();

/*
 * Loader Show Function
 */
function wp_dp_show_loader(loading_element, loader_data, loader_style, thisObj) {
    var loader_div = ".wp_dp_loader";
    if (loader_style == "button_loader") {
        loader_div = ".wp-dp-button-loader";
        if (thisObj != "undefined" && thisObj != "") {
            thisObj.addClass("wp-dp-processing");
        }
    }
    if (typeof loader_data !== "undefined" && loader_data != "" && typeof jQuery(loader_div) !== "undefined") {
        jQuery(loader_div).html(loader_data);
    }
    if (typeof loading_element !== "undefined" && loading_element != "" && typeof jQuery(loader_div) !== "undefined") {
        jQuery(loader_div).appendTo(loading_element);
    }
    jQuery(loader_div).css({
        display: "flex",
        display: "-webkit-box",
        display: "-moz-box",
        display: "-ms-flexbox",
        display: "-webkit-flex"
    });
}

/*
 * Loader Show Response Function
 */
function wp_dp_show_response(loader_data, loading_element, thisObj, clickTriger) {

    if (thisObj != "undefined" && thisObj != "" && thisObj != undefined) {
        thisObj.removeClass("wp-dp-processing");
    }
    jQuery(".wp-dp-button-loader").appendTo("#footer");
    jQuery(".wp_dp_loader").hide();
    jQuery(".wp-dp-button-loader").hide();
    if (clickTriger != "undefined" && clickTriger != "" && clickTriger != undefined) {
        jQuery(clickTriger).click();
    }
    jQuery("#growls").removeClass("wp_dp_element_growl");
    jQuery("#growls").find(".growl").remove();
    if (loader_data != "undefined" && loader_data != "") {
        if (loader_data.type != "undefined" && loader_data.type == "error") {
            var error_message = jQuery.growl.error({
                message: loader_data.msg
            });
            if (loading_element != "undefined" && loading_element != undefined && loading_element != "") {
                jQuery("#growls").prependTo(loading_element);
                jQuery("#growls").addClass("wp_dp_element_growl");
                setTimeout(function () {
                    jQuery(".growl-close").trigger("click");
                }, 5e3);
            }
        } else if (loader_data.type != "undefined" && loader_data.type == "success") {
            var success_message = jQuery.growl.success({
                message: loader_data.msg
            });
            if (loading_element != "undefined" && loading_element != undefined && loading_element != "") {
                jQuery("#growls").prependTo(loading_element);
                jQuery("#growls").addClass("wp_dp_element_growl");
                setTimeout(function () {
                    jQuery(".growl-close").trigger("click");
                }, 5e3);
            }
        }
    }
}

/*
 * Loader Hide Function  
 */
function wp_dp_hide_loader() {
    jQuery(".wp_dp_loader").hide();
    jQuery(".wp_dp_loader").html(default_loader);
}

/*
 * Hide Button loader
 */

function wp_dp_hide_button_loader(processing_div) {
    "use strict";
    if (processing_div != "undefined" && processing_div != "" && processing_div != undefined) {
        jQuery(processing_div).removeClass("wp-dp-processing");
    }
    jQuery(".wp-dp-button-loader").hide();
    jQuery(".wp-dp-button-loader").html(default_button_loader);
}


jQuery(document).ajaxComplete(function () {
    if (jQuery("body").hasClass("rtl") == true) {
        jQuery('[data-toggle="popover"]').popover({
            placement: 'right'
        });
    } else {
        jQuery('[data-toggle="popover"]').popover();
    }
    // listing grid
    propertGridEqual = new equalHeight(".listing-grid");
    propertGridEqual.equalHeightActive();
    // listing grid

    // listing grid Masnory
    propertGridMasnory = new equalHeight(".masnory .listing-grid");
    propertGridMasnory.equalHeightDisable();
    // listing grid Masnory

    // listing medium modern
    propertMediumModernEqual = new equalHeight(".listing-medium.modern .text-holder");
    propertMediumModernEqual.equalHeightActive();
    // listing medium modern

    // listing-medium Advance
    propertMediumAdvanceEqual = new equalHeight(".listing-medium.advance-grid .text-holder");
    propertMediumAdvanceEqual.equalHeightActive();
    // listing-medium Advance

    // listing-grid Advance
    propertAdvanceEqual = new equalHeight(".listing-grid.advance-grid");
    propertAdvanceEqual.equalHeightDisable();
    propertAdvanceEqual.equalHeightActiveSubChild(".text-holder");
    // listing-grid Advance

    // listing-grid Modern
    propertModernEqual = new equalHeight(".listing-grid.modern");
    propertModernEqual.equalHeightDisable();
    propertModernEqual.equalHeightActiveSubChild(".text-holder");
    // listing-grid Modern

    // listing-grid Modern
    propertModernv1Equal = new equalHeight(".listing-grid.modern.v1");
    propertModernv1Equal.equalHeightActiveSubChild(".post-listing-footer");
    // listing-grid Modern

    // listing-grid default
    propertDefaultEqual = new equalHeight(".listing-grid.default");
    propertDefaultEqual.equalHeightDisable();
    propertDefaultEqual.equalHeightActiveSubChild(".text-holder");
    // listing-grid default

    // blog post grid
    blogGridEqual = new equalHeight(".blog.blog-grid .blog-post");
    blogGridEqual.equalHeightActive();
    // blog post grid

    // member-grid 
    memberGridEqual = new equalHeight(".member-grid .post-inner-member");
    memberGridEqual.equalHeightActive();
    // member-grid 

    // member-grid member-info
    memberInfoEqual = new equalHeight(".member-grid .member-info");
    memberInfoEqual.equalHeightActive();
    // member-grid member-info

    // top-locations
    topLocationsEqual = new equalHeight(".top-locations ul li .image-holder");
    topLocationsEqual.equalHeightActive();
    // top-locations 

    // listing-grid default
    topLocationsEqual = new equalHeight(".listing-grid.default .text-holder");
    topLocationsEqual.equalHeightActive();
    // listing-grid default

    // Dsidx Listings
    dsidxListings = new equalHeight("#dsidx-listings .dsidx-listing .dsidx-data");
    dsidxListings.equalHeightActive();
    // Dsidx Listings  



    jQuery(document).on("click", ".wp-dp-open-register-tab", function (e) {
        e.stopImmediatePropagation();
        jQuery(".wp-dp-open-register-button").click();
    });

    jQuery(document).on("click", ".wp-dp-open-signin-tab", function (e) {
        e.stopImmediatePropagation();
        if (jQuery("header").hasClass("transparent-header")) {
            jQuery(".wp-dp-open-signin-button").click();
        } else if (jQuery(".main-header").hasClass("fancy")) {
            jQuery(".wp-dp-open-register-button").click();
            jQuery(".user-tab-login").click();
        } else {
            jQuery(".wp-dp-open-signin-button").click();
        }
    });
});



jQuery(document).on("click", ".dp-pretty-photos", function (e) {
    "use strict";

    //   jQuery(this).off("click");
    //    
    //    jQuery(this).addClass('disable-click');

    var id = jQuery(this).data('id');
    var rand_id = jQuery(this).data('rand');
    //echo rand_id;
    var galleryObj = jQuery(this).closest('#galley-img' + rand_id + '');
    jQuery(this).closest('#galley-img' + rand_id + '').find("i").removeClass('icon-camera6');
    jQuery(this).closest('#galley-img' + rand_id + '').find("i").addClass('fancy-spinner');

    var is_class_exists = jQuery(this).parent().hasClass('disable-click');

    if (!is_class_exists) {
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: wp_dp_globals.ajax_url,
            data: "action=wp_dp_gallery_photo_render&listing_id=" + id + '&listing_rand="' + rand_id,
            success: function (response) {
                galleryObj.html(response);
                jQuery("#galley-img" + rand_id + " a[data-rel^='prettyPhoto']").prettyPhoto();
                jQuery(".btnnn" + rand_id + "").trigger("click");

            }
        });
    }

    jQuery(this).parent().addClass('disable-click');

});

$(document).on('click', '.first-big-image a, .all-dpian-images a', function () {

    $('.first-big-image a, .all-dpian-images a').removeClass('active');
    $(this).addClass('active');
    $('#gallery-expander').trigger('click');
});

$(document).on('click', '#gallery-expander', function () {
    "use strict";

    var _this = $(this);
    var listing_id = _this.data('id');
    var this_apender = $('#gallery-appender-' + listing_id);
    var this_loader = _this.find('.loader-img');

    var targetImg = '';
    if ($('.first-big-image').find('a.active').length > 0) {
        targetImg = $('.first-big-image').find('a.active').attr('data-id');
    } else if ($('.all-dpian-images').find('a.active').length > 0) {
        targetImg = $('.all-dpian-images').find('a.active').attr('data-id');
    }
    $('.first-big-image a, .all-dpian-images a').removeClass('active');

    if (this_apender.find("a").length > 0) {
        if (targetImg != '') {
            this_apender.find("a#" + targetImg).trigger('click');
        } else {
            this_apender.find("a:first").trigger('click');
        }
    } else {

        this_loader.html('<i class="fancy-spinner"></i>');
        var is_class_exists = jQuery(this).parent().hasClass('disable-click');
        if (!is_class_exists) {

            $.ajax({
                url: wp_dp_globals.ajax_url,
                method: "POST",
                data: {
                    listing_id: listing_id,
                    action: 'listing_detail_gallery_imgs_load'
                },
                dataType: "json"
            }).done(function (response) {
                this_apender.html(response.html);
                this_apender.find("a[data-rel^='prettyPhoto']").prettyPhoto();
                if (targetImg != '') {
                    this_apender.find("a#" + targetImg).trigger('click');
                } else {
                    this_apender.find("a:first").trigger('click');
                }
                this_loader.html('');
            }).fail(function () {
                this_loader.html('');
            });

        }
        jQuery(this).parent().addClass('disable-click');

    }
});


jQuery(document).on("click", ".wp-dp-open-register-tab", function (e) {
    e.stopImmediatePropagation();
    jQuery(".wp-dp-open-register-button").click();
});

jQuery(document).on("click", ".wp-dp-open-signin-tab", function (e) {
    e.stopImmediatePropagation();
    if (jQuery("header").hasClass("transparent-header")) {
        jQuery(".wp-dp-open-signin-button").click();
    } else if (jQuery(".main-header").hasClass("fancy")) {
        jQuery(".wp-dp-open-register-button").click();
        jQuery(".user-tab-login").click();
    } else {
        jQuery(".wp-dp-open-signin-button").click();
    }

});

jQuery(document).on("click", ".delete-hidden-listing", function () {
    var thisObj = jQuery(this);
    var listing_id = thisObj.data('id');
    var action_type = thisObj.data('type');
    var delete_icon_class = thisObj.find("i").attr('class');
    var loader_class = 'fancy-spinner';
    var dataString = 'listing_id=' + listing_id + '&action=wp_dp_removed_hidden_listings';
    jQuery('#id_confrmdiv').addClass(action_type);
    jQuery('#id_confrmdiv').show();
    jQuery('.' + action_type + ' #id_truebtn').click(function () {
        thisObj.find('i').removeClass(delete_icon_class);
        thisObj.find('i').addClass(loader_class);
        jQuery.ajax({
            type: "POST",
            url: wp_dp_globals.ajax_url,
            data: dataString,
            dataType: "json",
            success: function (response) {
                thisObj.find('i').removeClass(loader_class).addClass(delete_icon_class);
                if (response.status == true) {

                    thisObj.closest('li').hide('slow', function () {
                        thisObj.closest('li').remove();
                    });

                    var msg_obj = {
                        msg: response.message,
                        type: 'success'
                    };
                    wp_dp_show_response(msg_obj);
                }
            }
        });
        jQuery('#id_confrmdiv').hide();
        jQuery('#id_confrmdiv').removeClass(action_type);
        return false;
    });
    jQuery('#id_falsebtn').click(function () {
        jQuery('#id_confrmdiv').hide();
        jQuery('#id_confrmdiv').removeClass(action_type);
        return false;
    });
    return false;
});

jQuery(document).on("click", ".delete-prop-notes", function () {
    var thisObj = jQuery(this);
    var listing_id = thisObj.data('id');
    var action_type = thisObj.data('type');
    var delete_icon_class = thisObj.find("i").attr('class');
    var loader_class = 'fancy-spinner';
    var dataString = 'listing_id=' + listing_id + '&action=wp_dp_removed_listing_notes';
    jQuery('#id_confrmdiv').addClass(action_type);
    jQuery('#id_confrmdiv').show();
    jQuery('.' + action_type + ' #id_truebtn').click(function () {
        thisObj.find('i').removeClass(delete_icon_class);
        thisObj.find('i').addClass(loader_class);
        jQuery.ajax({
            type: "POST",
            url: wp_dp_globals.ajax_url,
            data: dataString,
            dataType: "json",
            success: function (response) {
                thisObj.find('i').removeClass(loader_class).addClass(delete_icon_class);
                if (response.status == true) {

                    thisObj.closest('li').hide('slow', function () {
                        thisObj.closest('li').remove();
                    });

                    var msg_obj = {
                        msg: response.message,
                        type: 'success'
                    };
                    wp_dp_show_response(msg_obj);
                }
            }
        });
        jQuery('#id_confrmdiv').hide();
        jQuery('#id_confrmdiv').removeClass(action_type);
        return false;
    });
    jQuery('#id_falsebtn').click(function () {
        jQuery('#id_confrmdiv').hide();
        jQuery('#id_confrmdiv').removeClass(action_type);
        return false;
    });
    return false;
});


jQuery(document).on('click', '.listing-visibility .listing-visibility-update', function () {
    "use strict";
    var thisObj = jQuery(this);
    var listing_id = jQuery(this).attr('data-id');
    var visibility_status = jQuery(this).find('span').text();
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_update_listing_visibility&listing_id=' + listing_id + '&visibility_status=' + visibility_status,
        dataType: 'json',
        success: function (response) {
            wp_dp_show_response(response);
            if (jQuery('[data-toggle="tooltip"]').length != '') {
                jQuery('.listing-visibility .listing-visibility-update').tooltip('hide');
            }
            if (typeof response.icon !== 'undefined' && response.icon != '') {
                var icon_class = thisObj.parent().find('i').attr('class');
                thisObj.parent().find('i').removeClass(icon_class).addClass(response.icon);
            }
            if (typeof response.label !== 'undefined' && response.label != '') {
                thisObj.parent().find('span').text(response.label);
            }
            if (typeof response.value !== 'undefined' && response.value === 'public') {
                thisObj.parent().find('i').css("color", "green");
                thisObj.parent().find('span').css("color", "green");

            } else {
                thisObj.parent().find('i').css("color", "red");
                thisObj.parent().find('span').css("color", "red");
            }
        }
    });
});


jQuery(document).on('click', '.review-images-btn', function () {
    jQuery('.review-images').click();
});


jQuery(document).on('change', 'input.review-images', function () {
    input = jQuery(this)[0];
    var filesAmount = input.files.length;
    jQuery('.reviews-images-holder ul').remove();
    jQuery('.reviews-images-holder').append('<ul></ul>');
    jQuery('.reset-all-review-images').removeClass('hidden');
    for (i = 0; i < filesAmount; i++) {
        var reader = new FileReader();
        reader.onload = function (event) {
            var image_html = '<li style="background:url(' + event.target.result + ') no-repeat; background-size:cover;">';
            image_html = image_html + '</li>';
            jQuery('.reviews-images-holder ul').append(image_html);
        }
        reader.readAsDataURL(input.files[i]);
    }

});

jQuery(document).on('click', '.reset-all-review-images', function () {
    jQuery(this).addClass('hidden');
    jQuery('.reviews-images-holder ul').remove();
    jQuery('.review-images').val('');
});



jQuery(document).on('click', '.change-enquiry-status', function () {
    var thisObj = jQuery(this);
    var data_id = jQuery(this).data('id');
    var data_type = jQuery(this).data('type');

    var icon_class = thisObj.find('i').attr('class');
    var loader_class = 'fancy-spinner';
    thisObj.find('i').removeClass(icon_class).addClass(loader_class);

    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        url: wp_dp_globals.ajax_url,
        data: "action=wp_dp_change_enquiry_status&enquiry_id=" + data_id + "&type=" + data_type,
        success: function (response) {
            wp_dp_show_response(response);
            thisObj.find('i').removeClass(loader_class).addClass(icon_class);
            var all_count = jQuery('.dashboard-nav').find('.accordian').find('b.count-all-enquiries').html();
            var submitted_count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-enquiries').html();
            var received_count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-enquiries').html();
            if (jQuery('#enuiry-' + data_id).hasClass('read')) {
                if (typeof all_count !== "undefined") {
                    var sum = 1;
                    var new_val = parseInt(all_count) + parseInt(sum);
                    jQuery('.dashboard-nav').find('.accordian').find('b.count-all-enquiries').html(new_val);
                }
                if (typeof submitted_count !== "undefined" && data_type === 'my') {
                    var sum = 1;
                    var new_val = parseInt(submitted_count) + parseInt(sum);
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-enquiries').html(new_val);
                }
                if (typeof received_count !== "undefined" && data_type === 'received') {
                    var sum = 1;
                    var new_val = parseInt(received_count) + parseInt(sum);
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-enquiries').html(new_val);
                }
            } else {
                if (typeof all_count !== "undefined") {
                    all_count -= 1;
                    jQuery('.dashboard-nav').find('.accordian').find('b.count-all-enquiries').html(all_count);
                }
                if (typeof submitted_count !== "undefined" && data_type === 'my') {
                    submitted_count -= 1;
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-enquiries').html(submitted_count);
                }
                if (typeof received_count !== "undefined" && data_type === 'received') {
                    received_count -= 1;
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-enquiries').html(received_count);
                }
            }

            thisObj.closest('li').removeClass('read');
            thisObj.closest('li').removeClass('unread');
            thisObj.closest('li').addClass(response.read_type);
            thisObj.find('.info-content span').text(response.label);

            return false;
        }
    });
    return false;
});



/*
 * Load More for member listing on member detail
 */
jQuery(document).on("click", ".member-listing-load", function () {
    var thisObj = jQuery(this);
    wp_dp_show_loader(".member-listing-load", "", "button_loader", thisObj);
    var current_page = jQuery("#listing_current_page").val();
    var max_num_pages = jQuery("#listing_max_num_pages").val();
    var listing_member_id = jQuery("#listing_member_id").val();
    var listing_per_page = jQuery("#listing_per_page").val();
    jQuery.ajax({
        type: 'POST',
        url: wp_dp_globals.ajax_url,
        data: 'current_page=' + current_page + '&listing_member_id=' + listing_member_id + '&listing_per_page=' + listing_per_page + '&action=wp_dp_get_member_listings',
        success: function (response) {
            current_page = parseInt(current_page) + 1;
            jQuery(".listing-append").append(response);
            jQuery("#listing_current_page").val(current_page);

            if (max_num_pages == current_page) {
                jQuery(".member-listing-load").hide();
            }
            wp_dp_hide_button_loader(thisObj);
            return false;
        }

    });

});



/*
 * Selecting Package and redirecting to listing add page.
 */
jQuery(document).on("click", ".listing-pkg-select", function () {
    var package_id = jQuery(this).data('id');
    var thisObj = jQuery(this);
    var form_data = jQuery('#packages-form-' + package_id).serialize();
    wp_dp_show_loader(thisObj, "", "button_loader", thisObj);
    jQuery.ajax({
        type: 'POST',
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_listing_pkg_select&' + form_data,
        success: function (response) {
            jQuery('body').append(response);
            wp_dp_hide_button_loader(thisObj);
        }
    });

});

/*Sticky Sidebar Function Start*/
//$(function(){
//   if (!!$('.sticky-map-sidebar').length) {
//      var el = $('.sticky-map-sidebar');
//      var stickyTop = $('.sticky-map-sidebar').offset().top;
//      var footerTop = $('#footer').offset().top;
//      var stickyHeight = $('.sticky-map-sidebar').height();
//      var limit = footerTop - stickyHeight - 25;
//      $(window).scroll(function(){
//          var windowTop = $(window).scrollTop();
//            
//          if (stickyTop < windowTop){
//             el.css({ position: 'fixed', top: 0, 'padding-top':15});
//          }
//          else {
//             el.css({ position: 'static', 'padding-top': 0});
//          }
//            
//          if (limit < windowTop) {
//          var diff = limit - windowTop;
//          el.css({top: diff});
//          }     
//        });
//   }
//});
/*Sticky Sidebar Function End*/