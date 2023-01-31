var $   = jQuery;

jQuery(document).ready(function($) {
    if (jQuery(".wp_dp_editor").length != "") {
        jQuery(".wp_dp_editor").jqte();
    }
    jQuery(".cs-drag-slider").each(function(index) {
    "use strict";
    if (jQuery(this).attr("data-slider-step") != "") {
        var data_min_max = jQuery(this).attr("data-min-max");
        var val_parameter = [parseInt(jQuery(this).attr("data-slider-min")), parseInt(jQuery(this).attr("data-slider-max"))];
        if (data_min_max != "yes") {
            var val_parameter = parseInt(jQuery(this).attr("data-slider-min"));
        }
        jQuery(this).children("input").slider({
            min: parseInt(jQuery(this).attr("data-slider-min")),
            max: parseInt(jQuery(this).attr("data-slider-max")),
            value: val_parameter,
            focus: true
        });
    }
    });
	
});

jQuery(document).on("click", "a.wp-dp-dev-listing-delete", function() {
    "use strict";
    jQuery("#id_confrmdiv").show();
    var deleting_listing, _this_ = jQuery(this),
    _this_id = jQuery(this).data("id"),
    ajax_url = jQuery("#wp-dp-dev-user-listing").data("ajax-url"),
    this_parent = jQuery("#user-listing-" + _this_id);
    _this_.html('<i class="fancy-spinner"></i>');
    jQuery("#id_truebtn").click(function() {
        jQuery("#id_confrmdiv").hide();
        deleting_listing = jQuery.ajax({
            url: ajax_url,
            method: "POST",
            data: {
                listing_id: _this_id,
                action: "wp_dp_member_listing_delete"
            },
            dataType: "json"
        }).done(function(response) {
            if (typeof response.delete !== "undefined" && response.delete == "true") {
                this_parent.hide("slow");
            }
            _this_.html('<i class="icon-close2"></i>');
        }).fail(function() {
            _this_.html('<i class="icon-close2"></i>');
        });
    });

    jQuery("#id_falsebtn").click(function() {
        _this_.html('<i class="icon-close2"></i>');
        jQuery("#id_confrmdiv").hide();
        return false;
    });

});


jQuery(".book-list #close-btn4").click(function() {
    "use strict";
    jQuery(".book-list .open-close-time").addClass("opening-time");
});

jQuery(".book-list #close-btn3").click(function() {
    "use strict";
    jQuery(".book-list .open-close-time").removeClass("opening-time");
});


jQuery(".service-list ul li a.edit").on("click", function(e) {
    "use strict";
    e.preventDefault();
    jQuery(this).parent().toggleClass("open").find(".service-list ul li .info-holder");
    jQuery(this).parent().siblings().find(".service-list ul li .info-holder");
    jQuery(this).parent().siblings().removeClass("open");
});


/*
 * Packages
 */
jQuery(document).on("click", ".wp-dp-subscribe-pkg", function() {
    "use strict";
    var id = jQuery(this).data("id");
    jQuery("#response-" + id).slideDown();
});

$(document).on("click", ".wp-dp-dev-dash-detail-pkg", function() {
    "use strict";
    var _this_id = $(this).data("id"),
        package_detail_sec = $("#package-detail-" + _this_id);
    if (!package_detail_sec.is(":visible")) {
        $(".all-pckgs-sec").find(".package-info-sec").hide();
        package_detail_sec.slideDown();
    } else {
        package_detail_sec.slideUp();
    }
});

jQuery(document).on("click", ".wp-dp-subscribe-pkg-btn .buy-btn", function() {
    "use strict";
    var pkg_id = jQuery(this).parent().attr("data-id");
    var thisObj = jQuery(".buy-btn-" + pkg_id);
    wp_dp_show_loader(".buy-btn-" + pkg_id, "", "button_loader", thisObj);
});


/*
 * Open Time Block
 */

/* Time Open Close Function Start */

jQuery(".time-list #close-btn2").click(function() {
    jQuery(".time-list .open-close-time").addClass("opening-time");
});

jQuery(".time-list #close-btn1").click(function() {
    jQuery(".time-list .open-close-time").removeClass("opening-time");
});
    

jQuery(document).on("click", 'a[id^="wp-dp-dev-open-time"]', function() {
    "use strict";
    var _this_id = jQuery(this).data("id"),
        _this_day = jQuery(this).data("day"),
        _this_con = jQuery("#open-close-con-" + _this_day + "-" + _this_id),
        _this_status = jQuery("#wp-dp-dev-open-day-" + _this_day + "-" + _this_id);
    if (typeof _this_id !== "undefined" && typeof _this_day !== "undefined") {
        _this_status.val("on");
        _this_con.addClass("opening-time");
    }
});

jQuery(document).on("click", 'a[id^="wp-dp-dev-close-time"]', function() {
    "use strict";
    var _this_id = jQuery(this).data("id"),
        _this_day = jQuery(this).data("day"),
        _this_con = jQuery("#open-close-con-" + _this_day + "-" + _this_id),
        _this_status = jQuery("#wp-dp-dev-open-day-" + _this_day + "-" + _this_id);
    if (typeof _this_id !== "undefined" && typeof _this_day !== "undefined") {
        _this_status.val("");
        _this_con.removeClass("opening-time");
    }
});

/*
 * sorting gallery images
 */

function wp_dp_gallery_sorting_list(id, random_id) {
    var gallery = [];
    // more efficient than new Array()
    jQuery("#gallery_sortable_" + random_id + " li").each(function() {
        var data_value = jQuery.trim(jQuery(this).data("attachment_id"));
        gallery.push(jQuery(this).data("attachment_id"));
    });
    jQuery("#" + id).val(gallery.toString());
}


function wp_dp_load_location_ajax(postfix, allowed_location_types, location_levels, security) {
    "use strict";
    var $ = jQuery;
    $('#loc_country_' + postfix).change(function () {
        popuplate_data(this, 'country');
    });

    $('#loc_state_' + postfix).change(function () {
        popuplate_data(this, 'state');
    });

    $('#loc_city_' + postfix).change(function () {
        popuplate_data(this, 'city');
    });

    $('#loc_town_' + postfix).change(function () {
        popuplate_data(this, 'town');
    });

    function popuplate_data(elem, type) {
        "use strict";
        var plugin_url = $(elem).parents("#locations_wrap").data('plugin_url');
        var ajaxurl = $(elem).parents("#locations_wrap").data('ajaxurl');

        var index = allowed_location_types.indexOf(type);
        if (index + 1 >= allowed_location_types.length) {
            return;
        }
        var location_type = allowed_location_types[ index + 1 ];
        $(".loader-" + location_type + "-" + postfix).html("<img src='" + plugin_url + "/assets/backend/images/ajax-loader.gif' />").show();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "get_locations_list",
                security: security,
                location_type: location_type,
                location_level: location_levels[ location_type ],
                selector: elem.value,
            },
            dataType: "json",
            success: function (response) {
                if (response.error == true) {
                    return;
                }
                var control_selector = "#loc_" + location_type + "_" + postfix;
                var data = response.data;
                $(control_selector + ' option').remove();
                $(control_selector).append($("<option></option>").attr("value", '').text('Choose...'));
                $.each(data, function (key, term) {
                    $(control_selector).append($("<option></option>").attr("value", term.slug).text(term.name));
                });

                $(".loader-" + location_type + "-" + postfix).html('').hide();
                // Only for style implementation.
                $(".chosen-select").data("placeholder", "Select").trigger('chosen:updated');
            }
        });
    }

    jQuery(document).ready(function (e) {

        //changeMap();
        jQuery('input#wp-dp-search-location').keypress(function (e) {
            if (e.which == '13') {
                e.preventDefault();
                cs_search_map(this.value);
                return false;
            }
        });
        jQuery('#loc_country_listing').change(function (e) {
            setAutocompleteCountry('listing');
        });
        jQuery('#loc_country_member').change(function (e) {
            setAutocompleteCountry('member');
        });
        jQuery('#loc_country_default').change(function (e) {
            setAutocompleteCountry('default');
        });
    });
    function setAutocompleteCountry(type) {
        "use strict";
        var country = jQuery('select#loc_country_' + type + ' option:selected').attr('data-name'); /*document.getElementById('country').value;*/
        if (country != '') {
            autocomplete.setComponentRestrictions({'country': country});
        } else {
            autocomplete.setComponentRestrictions([]);
        }
    }

}
