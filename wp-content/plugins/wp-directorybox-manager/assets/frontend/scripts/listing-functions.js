var listingFilterAjax;
function wp_dp_listing_map_setter(counter, listings) {
    "use strict";
    var name = "wp_dp_listing_map_" + counter;
    var func = new Function(
            "return " + name + "(" + listings + ");"
            )();
}

function top_map_change_cords(counter) {
    "use strict";
    var top_map = jQuery('.wp-dp-ontop-gmap');
    if (top_map.length !== 0) {
        var ajax_url = wp_dp_globals.ajax_url;
        var data_vals = 'ajax_filter=true&map=top_map&action=wp_dp_top_map_search&' + jQuery(jQuery("#frm_listing_arg" + counter)[0].elements).not(":input[name='alerts-email'], :input[name='alert-frequency']").serialize();
        if (jQuery('form[name="wp-dp-top-map-form"]').length > 0) {
            data_vals += "&" + jQuery('form[name="wp-dp-top-map-form"]').serialize() + '&atts=' + jQuery('#atts').html();
        }
        data_vals = stripUrlParams(data_vals);
        var loading_top_map = $.ajax({
            url: ajax_url,
            method: "POST",
            data: data_vals,
            dataType: "json"
        }).done(function (response) {
            if (typeof response.html !== 'undefined') {
                jQuery('.top-map-action-scr').html(response.html);
            }
        }).fail(function () {
        });
    }
}

jQuery(document).on('change', '.dev-listing-list-enquiry-check', function () {
    var _this = $(this);
    var _this_id = _this.attr('data-id');
    var pop_buton = $('#prop-enquiry-pop-list-box');
    var _appending_inp = $('#prop-enquiry-list-all');
    if (_this.is(":checked")) {
        if (_appending_inp.val() == '') {
            _appending_inp.val(_this_id);
        } else {
            var new_val = _appending_inp.val() + ',' + _this_id;
            _appending_inp.val(new_val);
        }
    } else {
        if (_appending_inp.val() != '') {
            var strVal = _appending_inp.val();
            var dataArray = strVal.split(",");
            var valIndex = dataArray.indexOf(_this_id);
            if (valIndex > -1) {
                dataArray.splice(valIndex, 1);
                strVal = dataArray.join(',');
            }
            _appending_inp.val(strVal);
        }
    }
    if (_appending_inp.val() != '') {
        pop_buton.show();
    } else {
        pop_buton.hide();
    }
    pop_buton.find('#wp_dp_listing_id').val(_appending_inp.val());
});

jQuery(document).on('click', '.dev-prop-notes-login', function () {
    $('#sign-in').modal('show');
    $('#sign-in').find('div[id^="user-login-tab-"]').addClass('active in');
    $('#sign-in').find('div[id^="user-register-"]').removeClass('active in');
});

jQuery(document).on('click', '.submit-prop-notes', function () {

    var returnType = wp_dp_validation_process(jQuery(".prop-not"));
    if (returnType == false) {
        return false;
    }

    var ajax_url = wp_dp_globals.ajax_url;
    var that = $(this);
    var prop_id = $(this).attr('data-id');
    var prop_rand_id = $(this).attr('data-rand');

    var prop_notes = $('#prop-notes-text-' + prop_rand_id).val();

    var prop_note_btn = $('#listing-note-' + prop_rand_id);

    if (prop_notes != '') {
        that.prop('disabled', true);
        var thisObj = $('#prop-notes-model-popup').find(".submit-prop-notes-btn");
        wp_dp_show_loader("#prop-notes-model-popup .submit-prop-notes-btn", "", "button_loader", thisObj);
        var data_vals = 'adding_notes=true&prop_id=' + prop_id + '&prop_notes=' + prop_notes + '&action=wp_dp_adding_listing_notes';
        $.ajax({
            url: ajax_url,
            method: "POST",
            data: data_vals,
            dataType: "json"
        }).done(function (response) {
            wp_dp_show_response(response, '', thisObj);
            that.prop('disabled', false);
            if (response.type == 'success') {
                $('#prop-notes-model-popup').find('textarea').val('');
                $('#prop-notes-model-popup').modal('toggle');
                prop_note_btn.removeAttr('data-toggle');
                prop_note_btn.removeAttr('data-target');
                prop_note_btn.removeAttr('href');
                //var new_html = prop_note_btn.attr('data-afterlabel') + ' ' + '<i class="' + prop_note_btn.attr('data-aftericon') + '"></i>';
                var new_html = '<i class="' + prop_note_btn.attr('data-aftericon') + '"></i>' + '<div class="option-content"><span>' + prop_note_btn.attr('data-afterlabel') + '</span></div>';
                prop_note_btn.html(new_html);
            }
        }).fail(function () {
            that.prop('disabled', false);
        });
    } else {
        var thisObj = $('#prop-notes-model-popup').find(".submit-prop-notes-btn");
        wp_dp_show_loader("#prop-notes-model-popup .submit-prop-notes-btn", "", "button_loader", thisObj);
        var msg_obj = {msg: wp_dp_globals.some_txt_error, type: 'error'};
        wp_dp_show_response(msg_obj, '', thisObj);

    }
});

jQuery(document).on('click', '.listing-notes', function () {
    var this_id = $(this).attr('data-id');
    var this_href = $(this).attr('data-href');
    var this_title = $(this).attr('data-title');
    var rand_id = $(this).attr('data-rand');

    var popup_box = $('#prop-notes-model-popup');

    popup_box.find('.modal-header a.listing-title-notes').html(this_title);
    popup_box.find('.modal-header a.listing-title-notes').attr('href', this_href);
    popup_box.find('.modal-body .listing-notes-error').attr('id', 'listing-notes-error-' + this_id);
    popup_box.find('.modal-body textarea').attr('id', 'prop-notes-text-' + rand_id);
    popup_box.find('.modal-body input.submit-prop-notes').attr('data-id', this_id);
    popup_box.find('.modal-body input.submit-prop-notes').attr('data-rand', rand_id);

});

jQuery(document).ready(function () {
    jQuery(function () {
        var $checkboxes = jQuery("input[type=checkbox]");
        $checkboxes.on('change', function () {
            var ids = $checkboxes.filter(':checked').map(function () {
                return this.id;
            }).get().join(',');
            jQuery('#hidden_input').val(ids);
        });
    });
});

function wp_dp_listing_content(counter, view_type, animate_to) {
    //"use strict";
    var view_type = view_type || '';

    counter = counter || '';
    animate_to = animate_to || '';
    var view_type = view_type || '';
    // move to top when search filter apply

    if (animate_to != 'false') {
        if (jQuery("#wp-dp-listing-content-" + counter).offset() != 'undefined' && jQuery("#wp-dp-listing-content-" + counter).offset() != undefined) {
            jQuery('html, body').animate({
                scrollTop: jQuery("#wp-dp-listing-content-" + counter).offset().top - 120
            }, 700);
        }
    }
    var listing_arg = jQuery("#listing_arg" + counter).html();
    var this_frm = jQuery("#frm_listing_arg" + counter);


    var split_map = jQuery(".wp-dp-split-map-wrap").size();
    if (split_map > 0) {
        view_type = 'split_map';
    }

    var ads_list_count = jQuery("#ads_list_count_" + counter).val();
    var ads_list_flag = jQuery("#ads_list_flag_" + counter).val();
    var list_flag_count = jQuery("#ads_list_flag_count_" + counter).val();
    var current_page_id = jQuery("#current_page_id").val();
    var $checkboxes = jQuery("input[type=checkbox].wp_dp_listing_category");

    var ids = '';
    if (jQuery("#frm_listing_arg" + counter).find('.filters-sidebar').length <= 0) {
        ids = $checkboxes.filter(':checked').map(function () {
            return this.value;
        }).get().join(',');
    }
    if (jQuery("#frm_listing_arg" + counter).length > 0) {

        var data_vals = jQuery(jQuery("#frm_listing_arg" + counter)[0].elements).not(":input[name='alerts-email'], :input[name='alert-frequency']").serialize();
        // alert(data_vals);
        if (jQuery('form[name="wp-dp-top-map-form"]').length > 0) {
            data_vals += "&" + jQuery('form[name="wp-dp-top-map-form"]').serialize();
        }
        //alert(data_vals);

        if (ids != '') {
            data_vals += '&listing_category=' + ids;
        }

        // alert(data_vals);

        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        sURLVariables.forEach(function (items, key) {
            var key_val = items.split('=');
            if (key_val[0] == 'location') {
                //data_vals += '&'+items;
            }
        });

        data_vals = data_vals.replace(/[^&]+=\.?(?:&|$)/g, ''); // remove extra and empty variables
        data_vals = data_vals.replace('undefined', ''); // remove extra and empty variables
        data_vals = data_vals + '&ajax_filter=true';
        data_vals = stripUrlParams(data_vals);

        if (!jQuery('body').hasClass('wp-dp-changing-view')) {
            // top map
            top_map_change_cords(counter);
        }

        jQuery('#Listing-content-' + counter + ' .listing').addClass('slide-loader');
        jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').addClass('slide-loader');


        var listingFilterAjax = jQuery.ajax({
            type: 'POST',
            dataType: 'HTML',
            url: wp_dp_globals.ajax_url,
            data: data_vals + '&action=wp_dp_listings_content&view_type=' + view_type + '&listing_arg=' + listing_arg + '&page_id=' + current_page_id,
            success: function (response) {
                jQuery('body').removeClass('wp-dp-changing-view');
                jQuery('#Listing-content-' + counter).html(response);
                // Replace double & from string.
                data_vals = data_vals.replace("&&", "&");
                var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals; //window.location.href;
                current_url = current_url.replace('&=undefined', ''); // remove extra and empty variables
                window.history.pushState(null, null, decodeURIComponent(current_url));
                jQuery(".chosen-select").chosen();
                jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').removeClass('slide-loader');
                wp_dp_hide_loader();
                // add class when image loaded
                jQuery(".listing-medium .img-holder img, .listing-grid .img-holder img").one("load", function () {
                    jQuery(this).parents(".img-holder").addClass("image-loaded");
                }).each(function () {
                    if (this.complete)
                        jQuery(this).load();
                });
                if (jQuery(".listing-medium.modern").length > 0) {
                    var imageUrlFind = jQuery(".listing-medium.modern .img-holder").css("background-image").match(/url\(["']?([^()]*)["']?\)/).pop();
                    if (imageUrlFind) {
                        jQuery(".listing-medium.modern .img-holder").addClass("image-loaded");
                    }
                }
                // add class when image loaded
            }
        });
    }
}

function wp_dp_listing_content_without_filters(counter, page_var, page_num, ajax_filter, view_type) {
    "use strict";
    counter = counter || '';
    var listing_arg = jQuery("#listing_arg" + counter).html();
    var data_vals = page_var + '=' + page_num;
    jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').addClass('slide-loader');
    if (typeof (listingFilterAjax) != 'undefined') {
        listingFilterAjax.abort();
    }



    listingFilterAjax = jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_dp_globals.ajax_url,
        data: data_vals + '&action=wp_dp_listings_content&view_type=' + view_type + '&listing_arg=' + listing_arg,
        success: function (response) {
            jQuery('#Listing-content-' + counter).html(response);
            // Replace double & from string.
            data_vals = data_vals.replace("&&", "&");
            var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals; //window.location.href;
            window.history.pushState(null, null, decodeURIComponent(current_url));
            jQuery(".chosen-select").chosen();
            jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').removeClass('slide-loader');
            wp_dp_hide_loader();
            // add class when image loaded
            jQuery(".listing-medium .img-holder img, .listing-grid .img-holder img").one("load", function () {
                jQuery(this).parents(".img-holder").addClass("image-loaded");
            }).each(function () {
                if (this.complete)
                    $(this).load();
            });
            if (jQuery(".listing-medium.modern").length > 0) {
                var imageUrlFind = jQuery(".listing-medium.modern .img-holder").css("background-image").match(/url\(["']?([^()]*)["']?\)/).pop();
                if (imageUrlFind) {
                    jQuery(".listing-medium.modern .img-holder").addClass("image-loaded");
                }
            }
            // add class when image loaded
        }
    });
}

function stripUrlParams(args) {
    "use strict";
    var parts = args.split("&");
    var comps = {};
    for (var i = parts.length - 1; i >= 0; i--) {
        var spl = parts[i].split("=");
        // Overwrite only if existing is empty.
        if (typeof comps[ spl[0] ] == "undefined" || (typeof comps[ spl[0] ] != "undefined" && comps[ spl[0] ] == '')) {
            comps[ spl[0] ] = spl[1];
        }
    }
    parts = [];
    for (var a in comps) {
        parts.push(a + "=" + comps[a]);
    }

    return parts.join('&');
}

function wp_dp_listing_filters_content(counter, page_var, page_num, tab) {
    "use strict";
    counter = counter || '';
    var listing_arg = jQuery("#listing_arg" + counter).html();
    var this_frm = jQuery("#frm_listing_arg" + counter);

    var ads_list_count = jQuery("#ads_list_count_" + counter).val();
    var ads_list_flag = jQuery("#ads_list_flag_" + counter).val();
    var list_flag_count = jQuery("#ads_list_flag_count_" + counter).val();
    var data_vals = 'tab=' + tab + '&' + page_var + '=' + page_num + '&ajax_filter=true';
    jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').addClass('slide-loader');
    if (typeof (listingFilterAjax) != 'undefined') {
        listingFilterAjax.abort();
    }
    listingFilterAjax = jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_dp_globals.ajax_url,
        data: data_vals + '&action=wp_dp_listings_filters_content&listing_arg=' + listing_arg,
        success: function (response) {

            jQuery('#listing-tab-content-' + counter).html(response);
            jQuery("#listing-tab-content-" + counter + ' .row').mixItUp({
                selectors: {
                    target: ".portfolio",
                }
            });
            //replace double & from string
            data_vals = data_vals.replace("&&", "&");
            var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals; //window.location.href;
            window.history.pushState(null, null, decodeURIComponent(current_url));
            jQuery(".chosen-select").chosen();
            jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').removeClass('slide-loader');
            // add class when image loaded
            jQuery(".listing-medium .img-holder img, .listing-grid .img-holder img").one("load", function () {
                jQuery(this).parents(".img-holder").addClass("image-loaded");
            }).each(function () {
                if (this.complete)
                    $(this).load();
            });
            // add class when image loaded
        }
    });

}



function wp_dp_listing_by_categories_filters_content(counter, page_var, page_num, tab) {
    "use strict";
    counter = counter || '';
    var listing_arg = jQuery("#listing_arg" + counter).html();
    var this_frm = jQuery("#frm_listing_arg" + counter);

    var ads_list_count = jQuery("#ads_list_count_" + counter).val();
    var ads_list_flag = jQuery("#ads_list_flag_" + counter).val();
    var list_flag_count = jQuery("#ads_list_flag_count_" + counter).val();
    var data_vals = 'tab=' + tab + '&' + page_var + '=' + page_num + '&ajax_filter=true';
    jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').addClass('slide-loader');
    if (typeof (listingFilterAjax) != 'undefined') {
        listingFilterAjax.abort();
    }
    listingFilterAjax = jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_dp_globals.ajax_url,
        data: data_vals + '&action=wp_dp_listing_by_categories_filters_content&listing_arg=' + listing_arg,
        success: function (response) {
            jQuery('#listing-tab-content-' + counter).html(response);
            jQuery("#listing-tab-content-" + counter + ' .row').mixItUp({
                selectors: {
                    target: ".portfolio",
                }
            });
            //replace double & from string
            data_vals = data_vals.replace("&&", "&");
            var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals; //window.location.href;
            window.history.pushState(null, null, decodeURIComponent(current_url));
            jQuery(".chosen-select").chosen();
            jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').removeClass('slide-loader');
            // add class when image loaded
            jQuery(".listing-medium .img-holder img, .listing-grid .img-holder img").one("load", function () {
                jQuery(this).parents(".img-holder").addClass("image-loaded");
            }).each(function () {
                if (this.complete)
                    $(this).load();
            });
            // add class when image loaded
        }
    });

}

function convertHTML(html) {
    "use strict";
    var newHtml = $.trim(html),
            $html = $(newHtml),
            $empty = $();

    $html.each(function (index, value) {
        if (value.nodeType === 1) {
            $empty = $empty.add(this);
        }
    });

    return $empty;
}

function wp_dp_listing_type_search_fields(thisObj, counter, price_switch, view_type) {
    var view_type = view_type || '';
    "use strict";
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: wp_dp_globals.ajax_url,
        data: '&action=wp_dp_listing_type_search_fields&listing_short_counter=' + counter + '&listing_type_slug=' + thisObj.value + '&price_switch=' + price_switch + '&view_type=' + view_type,
        success: function (response) {
            jQuery('#listing_type_fields_' + counter).html('');
            jQuery('#listing_type_fields_' + counter).html(response.html);
        }
    });

    var checkID = thisObj.getAttribute('id');

    var cat_name = $('#' + checkID).next('label').html();

    $('.map-search-keyword-type-holder .dropdown-types-btn').html(cat_name);

    var dropdownHolder = $('.map-search-keyword-type-holder');
    var dropdownCon = dropdownHolder.find('ul.dropdown-types');
    dropdownCon.slideUp();
}

function wp_dp_listing_type_cate_fields(thisObj, counter, cats_switch, view, color) {
    "use strict";
    if (typeof view === 'undefined') {
        view = 'default';
    }
    if (typeof color === 'undefined') {
        color = 'none';
    }
    var cate_loader = '<b class="spinner-label">' + wp_dp_listing_functions_string.listing_type + '</b><span class="cate-spinning"><i class="fancy-spinner"></i></span>';
    jQuery('#listing_type_cate_fields_' + counter).html(cate_loader);
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: wp_dp_globals.ajax_url,
        data: '&action=wp_dp_listing_type_cate_fields&listing_short_counter=' + counter + '&listing_type_slug=' + thisObj.value + '&view=' + view + '&color=' + color + '&cats_switch=' + cats_switch,
        success: function (response) {
            jQuery('#listing_type_cate_fields_' + counter).html('');
            jQuery('#listing_type_cate_fields_' + counter).html(response.html);
        }
    });
}

function wp_dp_listing_type_split_map_search_fields(thisObj, counter) {

    var view_type = view_type || '';
    "use strict";
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: wp_dp_globals.ajax_url,
        data: '&action=wp_dp_listing_type_split_map_search_fields&listing_short_counter=' + counter + '&listing_type_slug=' + thisObj.value,
        success: function (response) {
            jQuery('#listing_type_fields_' + counter).html('');
            jQuery('#listing_type_fields_' + counter).html(response.html);
        }
    }).done(function () {
        wp_dp_listing_type_split_map_cate_fields(thisObj.value, counter);
    });

    var checkID = thisObj.getAttribute('id');
    var cat_name = $('#' + checkID).next('label').html();
    $('.map-search-keyword-type-holder .dropdown-types-btn').html(cat_name);

    var dropdownHolder = $('.map-search-keyword-type-holder');
    var dropdownCon = dropdownHolder.find('ul.dropdown-types');
    dropdownCon.slideUp();
}

function wp_dp_listing_type_split_map_cate_fields(thisObj, counter, cats_switch, view, color) {
    "use strict";
    if (typeof view === 'undefined') {
        view = 'default';
    }
    if (typeof color === 'undefined') {
        color = 'none';
    }
    var cate_loader = '<b class="spinner-label">' + wp_dp_listing_functions_string.listing_type + '</b><span class="cate-spinning"><i class="fancy-spinner"></i></span>';
    jQuery('#listing_type_cate_fields_' + counter).html(cate_loader);
    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: wp_dp_globals.ajax_url,
        data: '&action=wp_dp_listing_type_split_map_cate_fields&listing_short_counter=' + counter + '&listing_type_slug=' + thisObj.value + '&view=' + view + '&color=' + color,
        success: function (response) {
            jQuery('#listing_type_cate_fields_' + counter).html('');
            jQuery('#listing_type_cate_fields_' + counter).html(response.html);
        }
    }).done(function () {
        wp_dp_split_map_change_cords(counter);
    });
}

function wp_dp_split_map_change_cords(counter, hide_overlay) {
    "use strict";
    var hide_overlay = hide_overlay || '';
    if (jQuery('.split-map .more-filters-btn').hasClass('open')) {
        if (hide_overlay == 'true') {
            hide_overlay = 'true';
        } else {
            hide_overlay = 'false';
        }
    } else {
        hide_overlay = 'true';
    }
    var top_map = jQuery('.wp-dp-ontop-gmap');
    var loader_div = jQuery('.wp-dp-splitmap-advance-filter_' + counter);
    var loader_html = '<div class="split-map-loader"><span><i class="fancy-spinner"></i></span></div>';
    if (loader_div.length !== 0) {
        loader_div.html(loader_html);
    }
    if (top_map.length !== 0) {
        var ajax_url = wp_dp_globals.ajax_url;
        var data_vals = 'ajax_filter=true&map=top_map&action=wp_dp_top_map_search&' + jQuery(jQuery("#frm_listing_arg" + counter)[0].elements).not(":input[name='alerts-email'], :input[name='alert-frequency']").serialize();
        if (jQuery('form[name="wp-dp-top-map-form"]').length > 0) {
            data_vals += "&" + jQuery('form[name="wp-dp-top-map-form"]').serialize() + '&atts=' + jQuery('#atts').html();
        }
        data_vals = stripUrlParams(data_vals);
        var loading_top_map = $.ajax({
            url: ajax_url,
            method: "POST",
            data: data_vals,
            dataType: "json"
        }).done(function (response) {
            if (typeof response.html !== 'undefined') {
                jQuery('.top-map-action-scr').html(response.html);
            }
            wp_dp_listing_split_map_content(counter, '', '', hide_overlay);
        }).fail(function () {
        });
    }
}

function removeURLParameter(url, parameter) {
    //prefer to use l.search if you have a location/link object
    var urlparts = url.split('?');
    if (urlparts.length >= 2) {

        var prefix = encodeURIComponent(parameter) + '=';
        var pars = urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i = pars.length; i-- > 0; ) {
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                pars.splice(i, 1);
            }
        }

        url = urlparts[0] + '?' + pars.join('&');
        return url;
    } else {
        return url;
    }
}


function wp_dp_listing_split_map_content(counter, view_type, animate_to, hide_overlay) {
    //"use strict";
    counter = counter || '';
    var hide_overlay = hide_overlay || '';
    animate_to = animate_to || '';
    var view_type = view_type || '';
    var loader_div = jQuery('.wp-dp-splitmap-advance-filter_' + counter);
    var loader_html = '<div class="split-map-loader"><span><i class="fancy-spinner"></i></span></div>';
    // move to top when search filter apply


    if (animate_to != 'false') {
        jQuery('html, body').animate({
            scrollTop: jQuery("#wp-dp-listing-content-" + counter).offset().top - 120
        }, 700);
    }
    var listing_arg = jQuery("#listing_arg" + counter).html();
    var this_frm = jQuery("#frm_listing_arg" + counter);


    var split_map = jQuery(".wp-dp-split-map-wrap").size();
    if (split_map > 0) {
        view_type = 'split_map';
    }

    var ads_list_count = jQuery("#ads_list_count_" + counter).val();
    var ads_list_flag = jQuery("#ads_list_flag_" + counter).val();
    var list_flag_count = jQuery("#ads_list_flag_count_" + counter).val();

    if (jQuery("#frm_listing_arg" + counter).length > 0) {
        var data_vals = jQuery(jQuery("#frm_listing_arg" + counter)[0].elements).not(":input[name='alerts-email'], :input[name='alert-frequency']").serialize();
        var data_vals = 'ajax_filter=true&map=top_map&action=wp_dp_top_map_search&' + jQuery(jQuery("#frm_listing_arg" + counter)[0].elements).not(":input[name='alerts-email'], :input[name='alert-frequency']").serialize();
        if (jQuery('form[name="wp-dp-top-map-form"]').length > 0) {
            data_vals += "&" + jQuery('form[name="wp-dp-top-map-form"]').serialize();
        }
        data_vals = data_vals.replace(/[^&]+=\.?(?:&|$)/g, ''); // remove extra and empty variables
        data_vals = data_vals.replace('undefined', ''); // remove extra and empty variables
        data_vals = data_vals + '&ajax_filter=true';

        var location_param = getUrlParameter('location');
        var search_type = getUrlParameter('search_type');
        if (location_param != '') {
            if (search_type != '') {
                data_vals = data_vals + '&search_type=' + search_type;
            }
            data_vals = data_vals + '&location=' + location_param;
        }

        data_vals = stripUrlParams(data_vals);
        if (!jQuery('body').hasClass('wp-dp-changing-view')) {
            // top map
            //top_map_change_cords(counter);
        }

        if (hide_overlay === 'true') {
            data_vals = removeURLParameter(data_vals, 'adv_filter_toggle');
            data_vals = data_vals.replace('adv_filter_toggle=true', 'adv_filter_toggle=false');
        }

        jQuery('#Listing-content-' + counter + ' .listing').addClass('slide-loader');
        jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').addClass('slide-loader');
//        if (typeof (listingFilterAjax) !== 'undefined') {
//            listingFilterAjax.abort();
//        }

        //console.log(data_vals);
        var listingFilterAjax = jQuery.ajax({
            type: 'POST',
            dataType: 'HTML',
            url: wp_dp_globals.ajax_url,
            data: data_vals + '&action=wp_dp_listings_content&view_type=' + view_type + '&listing_arg=' + listing_arg,
            success: function (response) {
                jQuery('body').removeClass('wp-dp-changing-view');
                jQuery('#Listing-content-' + counter).html(response);

                if (hide_overlay === 'false' && hide_overlay !== '') {
                    jQuery('.main-search.split-map .field-holder.more-filters-btn').addClass('open');
                    jQuery('.split-map-container .split-map-fixed-filter').addClass('filter-fixed');
                    var split_map_container_width = jQuery(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder").outerWidth();
                    jQuery('.split-map-container .split-map-fixed-filter').css('width', split_map_container_width);

                }
                if (hide_overlay === 'true' && hide_overlay !== '') {
                    jQuery(".main-search.split-map .field-holder.more-filters-btn").removeClass('open');
                    jQuery('.split-map-container .split-map-fixed-filter').removeClass('filter-fixed');
                    jQuery('.split-map-container .split-map-fixed-filter').css('width', '');
                    jQuery("div.split-map-overlay").remove();
                }


                // Replace double & from string.
                data_vals = data_vals.replace('action=wp_dp_top_map_search', '');
                data_vals = data_vals.replace("&&", "&");
                var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals; //window.location.href;
                current_url = current_url.replace('&=undefined', ''); // remove extra and empty variables
                window.history.pushState(null, null, decodeURIComponent(current_url));
                jQuery(".chosen-select").chosen();
                jQuery('#wp-dp-data-listing-content-' + counter + ' .all-results').removeClass('slide-loader');
                wp_dp_hide_loader();
                // add class when image loaded
                jQuery(".listing-medium .img-holder img, .listing-grid .img-holder img").one("load", function () {
                    jQuery(this).parents(".img-holder").addClass("image-loaded");
                }).each(function () {
                    if (this.complete)
                        jQuery(this).load();
                });
                if (jQuery(".listing-medium.modern").length > 0) {
                    var imageUrlFind = jQuery(".listing-medium.modern .img-holder").css("background-image").match(/url\(["']?([^()]*)["']?\)/).pop();
                    if (imageUrlFind) {
                        jQuery(".listing-medium.modern .img-holder").addClass("image-loaded");
                    }
                }

                // add class when image loaded

                if (loader_div.length !== 0) {
                    loader_div.html('');
                }
            }
        });
    }
}

function wp_dp_empty_loc_polygon(counter) {
    if (jQuery("#frm_listing_arg" + counter + " input[name=loc_polygon_path]").length) {
        jQuery("#frm_listing_arg" + counter + " input[name=loc_polygon_path]").val('');
    }
}
function wp_dp_listing_view_switch(view, counter, listing_short_counter, view_type) {
    "use strict";

    jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_dp_globals.ajax_url,
        data: '&action=wp_dp_listing_view_switch&view=' + view + '&listing_short_counter=' + listing_short_counter,
        success: function () {
            jQuery('[data-toggle="popover"]').popover();
            jQuery('body').addClass('wp-dp-changing-view');
            wp_dp_listing_content(counter, view_type);
        }
    });
}
function wp_dp_listing_pagenation_ajax(page_var, page_num, counter, ajax_filter, view_type) {
    "use strict";
    var view_type = view_type || '';
    jQuery('html, body').animate({
        scrollTop: jQuery("#wp-dp-listing-content-" + counter).offset().top - 120
    }, 1000);
    jQuery('#' + page_var + '-' + counter).val(page_num);
    if (ajax_filter == 'false') {
        if (view_type == "split_map") {
            wp_dp_listing_content(counter, view_type);
        } else {
            wp_dp_listing_content_without_filters(counter, page_var, page_num, ajax_filter, view_type);
        }
    } else {
        wp_dp_listing_content(counter, view_type);
    }
}

function wp_dp_listing_filters_pagenation_ajax(page_var, page_num, counter, tab) {
    "use strict";
    jQuery('#' + page_var + '-' + counter).val(page_num);
    wp_dp_listing_filters_content(counter, page_var, page_num, tab);
}

function wp_dp_listing_by_category_filters_pagenation_ajax(page_var, page_num, counter, tab) {
    "use strict";
    jQuery('#' + page_var + '-' + counter).val(page_num);
    wp_dp_listing_by_categories_filters_content(counter, page_var, page_num, tab);
}

function wp_dp_advanced_search_field(counter, tab, element) {
    "use strict";
    if (tab == 'simple') {
        jQuery('#listing_type_fields_' + counter).addClass('filters-shown');
        jQuery('#nav-tabs-' + counter + ' li').removeClass('active');
        jQuery(element).parent().addClass('active');
    } else if (tab == 'advance') {
        jQuery('#listing_type_fields_' + counter).removeClass('filters-shown');
        jQuery('#nav-tabs-' + counter + ' li').removeClass('active');
        jQuery(element).parent().addClass('active');
    } else {
        jQuery('#listing_type_fields_' + counter).toggleClass('filters-shown');

        if (jQuery(".main-search.split-map .field-holder.more-filters-btn").length > 0) {
            jQuery('.main-search.split-map .field-holder.more-filters-btn').toggleClass('open');
            if (jQuery('.main-search.split-map .field-holder.more-filters-btn').hasClass('open')) {
                var NewContent = '<div class="split-map-overlay"></div>';
                jQuery(".wp-dp-top-map-holder").after(NewContent);
                jQuery('.split-map-container .split-map-fixed-filter').addClass('filter-fixed');
                var split_map_container_width = jQuery(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder").outerWidth();
                jQuery('.split-map-container .split-map-fixed-filter').css('width', split_map_container_width);
                jQuery('input[name="adv_filter_toggle"]').val('false');
            } else {
                jQuery("div.split-map-overlay").remove();
                jQuery('.split-map-container .split-map-fixed-filter').removeClass('filter-fixed');
                jQuery('.split-map-container .split-map-fixed-filter').css('width', '');
                jQuery(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder").css({"min-height": ''});
                jQuery('input[name="adv_filter_toggle"]').val('true');
            }
        }
    }
}

if (jQuery(".main-search.split-map .field-holder.more-filters-btn").length > 0) {
    function wp_dp_split_map_close_search(counter) {
        jQuery(".main-search.split-map .field-holder.more-filters-btn").toggleClass('open');
        jQuery("div.split-map-overlay").remove();
        jQuery('#listing_type_fields_' + counter).toggleClass('filters-shown');
        jQuery('.split-map-container .split-map-fixed-filter').removeClass('filter-fixed');
        jQuery('.split-map-container .split-map-fixed-filter').css('width', '');
        jQuery(".wp-dp-split-map-wrap .split-map-container .slide-loader-holder").css({"min-height": ''});
    }
}

function wp_dp_search_features(element, counter) {
    "use strict";
    jQuery('#listing_type_fields_' + counter + ' .features-field-expand').toggleClass('filters-shown');
    var expand_class = jQuery('#listing_type_fields_' + counter + ' .features-list .advance-trigger').find('i').attr('class');
    if (expand_class == 'icon-plus') {
        jQuery('#listing_type_fields_' + counter + ' .features-list .advance-trigger').find('i').removeClass(expand_class).addClass('icon-minus')
    } else {
        jQuery('#listing_type_fields_' + counter + ' .features-list .advance-trigger').find('i').removeClass(expand_class).addClass('icon-plus')
    }
}


jQuery(document).on('click', '.dev-listing-list-enquiry-own-listing', function (e) {
    e.stopImmediatePropagation();
    var msg_obj = {msg: wp_dp_globals.own_listing_error, type: 'error'};
    wp_dp_show_response(msg_obj);
});


/*
 * Enquiries Block
 */

jQuery(document).ready(function () {
    if (jQuery('#enquires-sidebar-panel').length > 0) {
        enquiry_sidebar_arrow();
    }
});

function enquiry_sidebar_arrow() {
    jQuery('.fixed-sidebar-panel.left .sidebar-panel-btn').click(function (e) {
        e.preventDefault();
        if (jQuery('#enquires-sidebar-panel').hasClass('sidebar-panel-open')) {
            jQuery('#enquires-sidebar-panel').removeClass('sidebar-panel-open');
        } else {
            jQuery('#enquires-sidebar-panel').addClass('sidebar-panel-open');
        }
    });
    jQuery('#enquires-sidebar-panel .sidebar-panel-title .sidebar-panel-btn-close').click(function (e) {
        jQuery('#enquires-sidebar-panel').removeClass('sidebar-panel-open');
    });
}
/*
 * Enquiry Multiple select
 */

jQuery(document).on('click', '.listing-list-enquiry-check', function () {
    var data_id = jQuery(this).data('id');
    var thisObj = jQuery(this);
    if (thisObj.hasClass('active')) {
        jQuery('.chosen-enquires-list .sidebar-listings-list ul li[data-id="' + data_id + '"] .listing-item-dpove').click();
        return;
    }
    thisObj.append('<span class="enquiry-loader"><i class="fancy-spinner"></i></span>');
    jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_enquiry_list_frontend&listing_id=' + data_id + '&add_enquiry=yes',
        success: function (response) {
            thisObj.find('.enquiry-loader').remove();
            jQuery('.chosen-enquires-list .sidebar-listings-list ul').append(response);
            if (response != '') {
                thisObj.addClass('active');
                if (!jQuery('.chosen-enquires-list').hasClass('sidebar-panel-open')) {
                    jQuery('#enquires-sidebar-panel').addClass('sidebar-panel-open');
                    jQuery('#enquires-sidebar-panel .sidebar-panel-btn').fadeIn('slow');
                }
                var _appending_inp = jQuery("#wp_dp_listing_id");
                if (_appending_inp.val() == '') {
                    _appending_inp.val(data_id);
                } else {
                    var new_val = _appending_inp.val() + ',' + data_id;
                    _appending_inp.val(new_val);
                }
            }
        }
    });
});

/*
 * Enquiry Remove from Multiple select
 */

jQuery(document).on('click', '.chosen-enquires-list .sidebar-listings-list ul li .listing-item-dpove', function () {
    var thisObj = jQuery(this);
    var data_id = thisObj.closest('li').data('id');
    thisObj.html('<i class="fancy-spinner"></i>');
    jQuery('.listing-list-enquiry-check[data-id="' + data_id + '"]').append('<span class="enquiry-loader"><i class="fancy-spinner"></i></span>');
    jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_enquiry_list_remove_frontend&listing_id=' + data_id,
        success: function (response) {
            jQuery('.listing-list-enquiry-check[data-id="' + data_id + '"]').find('.enquiry-loader').remove();
            jQuery('.listing-list-enquiry-check[data-id="' + data_id + '"]').removeClass('active');
            var strVal = jQuery("#wp_dp_listing_id").val();
            var dataArray = strVal.split(",");
            dataArray.splice(dataArray.indexOf(data_id), 1);
            strVal = dataArray.join(',');
            jQuery("#wp_dp_listing_id").val(strVal);
            if (strVal == '') {
                jQuery('#enquires-sidebar-panel').removeClass('sidebar-panel-open');
                jQuery('#enquires-sidebar-panel .sidebar-panel-btn').fadeOut('slow');
            }
            thisObj.closest('li').slideUp(400, function () {
                thisObj.closest('li').remove();
            });
        }
    });
});


function SidbarPanelHeight() {
    var WindowHeightForSidbarPanel = $(window).height();
    $(".sidebar-listings-list ul").css({"max-height": WindowHeightForSidbarPanel - 200, "overflow-y": "auto"});
}
SidbarPanelHeight();
$(window).resize(function () {
    SidbarPanelHeight();
});

/*
 * Enquiry Reset all Multiple select
 */

jQuery(document).on('click', '.chosen-enquires-list .enquiry-reset-btn', function () {
    var thisObj = jQuery(this);
    wp_dp_show_loader(".chosen-enquires-list .enquiry-reset-btn", "", "button_loader", thisObj);
    jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_enquiry_list_clear_frontend',
        success: function (response) {
            jQuery('.chosen-enquires-list .sidebar-listings-list ul li').remove();
            wp_dp_hide_button_loader('.chosen-enquires-list .enquiry-reset-btn');
            jQuery('.listing-list-enquiry-check').removeClass('active');
            jQuery("#wp_dp_listing_id").val('');
            jQuery('#enquires-sidebar-panel').removeClass('sidebar-panel-open');
            jQuery('#enquires-sidebar-panel .sidebar-panel-btn').fadeOut('slow');
        }
    });
});

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
}