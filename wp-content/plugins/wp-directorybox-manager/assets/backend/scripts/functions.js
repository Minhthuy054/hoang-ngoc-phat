/*
 * hide page section
 */


var $ = jQuery;

function getParameterByName(name, url) {
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
jQuery(document).ready(function () {
    var code = getParameterByName('code');
    var state = getParameterByName('state');
    if (typeof code !== "undefined" && code !== null && typeof state !== "undefined" && state !== null) {
        var newURLString = window.location.href + "#tab-api-setting-show";
        window.location.href = newURLString;
    }
});

jQuery(document).ready(function ($) {

    "use strict";

    $('[data-toggle="popover"]').popover();

    popup_view_box();


    if ($("#cs-pb-formelements").length != '') {
        $('#cs-pb-formelements').sortable();
    }

    if ($("#cs-pb-formelements_form_builder").length != '') {
        $('#cs-pb-formelements_form_builder').sortable();
    }
    $('.bg_color').wpColorPicker();

    /*
     * Media Upload 
     */
    var contheight;
    jQuery(document).on("click", ".uploadMedia, .cs-uploadMedia", function () {
        "use strict";
        var $ = jQuery;
        var id = $(this).attr("name");
        var custom_uploader = wp.media({
            title: 'Select File',
            button: {
                text: 'Add File'
            },
            multiple: false
        })
                .on('select', function () {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    jQuery('#' + id).val(attachment.id);
                    jQuery('#' + id).change();
                    jQuery('#' + id + '_img').attr('src', attachment.url);
                    jQuery('#' + id + '_box').show();

                }).open();

    });

    jQuery(document).on("click", ".cs-attachment-uploadMedia", function () {
        "use strict";
        var $ = jQuery;
        var allowd_extensions = $(this).attr("allowd_extensions");
        var id = $(this).data("id");
        var new_allowed_extentions = new Array();
        new_allowed_extentions = allowd_extensions.split(",");
        var id = $(this).attr("name");
        jQuery('span.' + id + '_allowed_extensions').css('color', '');
        var custom_uploader = wp.media({
            title: 'Select File',
            button: {
                text: 'Add File'
            },
            multiple: false
        })
                .on('select', function () {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    var ext = attachment.filename.match(/\.(.+)$/)[1];
                    if ($.inArray(ext, new_allowed_extentions) == -1 && allowd_extensions != '') {
                        alert('Invalid Extension!');
                        jQuery('span.' + id + '_allowed_extensions').css('color', 'red');
                        return false;
                    } else if (allowd_extensions != '') {
                        jQuery('#' + id).val(attachment.id);
                        jQuery('#' + id + '_img').attr('src', wp_dp_chosen_vars.plugin_url + '/assets/common/attachment-images/attach-' + ext + '.png');
                        jQuery('#' + id + '_box').show();
                    } else {
                        jQuery('#' + id).val(attachment.id);
                        jQuery('#' + id + '_img').attr('src', attachment.url);
                        jQuery('#' + id + '_box').show();
                    }
                }).open();

    });
    /*
     * Update Team Member
     */
    jQuery(document).on('click', '#team_update_form_backend', function () {

        jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);
        var user_id = jQuery(this).closest('li').data('id');
        // wp_dp_show_loader();
        var serializedValues = jQuery("#wp_dp_update_team_member" + user_id).serialize();
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: wp_dp_backend_globals.ajax_url,
            data: serializedValues + '&wp_dp_user_id=' + user_id + '&action=wp_dp_update_team_member',
            success: function (response) {
                jQuery(".loading_div").hide();
                jQuery(".form-msg .innermsg").html(response.msg);
                jQuery(".form-msg").show();
                jQuery(".outerwrapp-layer").delay(3000).fadeOut(500);
                slideout();
                //wp_dp_show_response(response);
            }


        });
    });


    jQuery(document).on('click', '.changeicon', function () {
        jQuery('#wp_dp_map_t_op_search').click();

    });

    /*
     * Remove Team Member
     */
    jQuery(document).on('click', '.remove_member', function () {
        "use strict";
        var thisObj = jQuery(this);
        var user_id = thisObj.closest('form').data('id');
        var count_supper_admin = thisObj.closest('form').data('count_supper_admin');
        var selected_user_type = thisObj.closest('form').data('selected_user_type');
        wp_dp_show_loader();
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: wp_dp_backend_globals.ajax_url,
            data: 'wp_dp_user_id=' + user_id + '&selected_user_type=' + selected_user_type + '&count_supper_admin=' + count_supper_admin + '&action=wp_dp_remove_team_member',
            success: function (response) {
                if (response.type == 'success') {
                    jQuery('#wp_dp_member_company').trigger('click');
                    thisObj.closest('form').fadeOut('slow');

                }
                wp_dp_show_response(response);
            }
        });
    });

//load role related profile fields
    jQuery(document).on("change", "#role", function () {
        "use strict";
        var selected_role = jQuery(this).find(":selected").val();
        if (selected_role == 'wp_dp_member') {
            jQuery(".wp-dp-user-customfield-block").show();
        } else {
            jQuery(".wp-dp-user-customfield-block").hide();
        }

    });

    /*
     * hide page section
     */

    var myUrl = window.location.href; //get URL
    var myUrlTab = myUrl.substring(myUrl.indexOf("#")); // For localhost/tabs.html#tab2, myUrlTab = #tab2     
    var myUrlTabName = myUrlTab.substring(0, 4); // For the above example, myUrlTabName = #tab
    jQuery("#tabbed-content > div").addClass('hidden-tab'); // Initially hide all content #####EDITED#####
    jQuery("#wp-dp-options-tab li:first a").attr("id", "current"); // Activate first tab
    jQuery("#tabbed-content > div:first").hide().removeClass('hidden-tab').fadeIn(); // Show first tab content   #####EDITED#####
    jQuery("#wp-dp-options-tab > li:first").addClass('active');

    jQuery(document).on("click", "#wp-dp-options-tab li > a", function (e) {
        e.preventDefault();
        if (jQuery(this).attr("id") == "current") { //detection for current tab
            return
        } else {
            wp_dp_reset_tabs();
            jQuery("#wp-dp-options-tab > li").removeClass('active')
            jQuery(this).attr("id", "current"); // Activate this
            jQuery(this).parents('li').addClass('active');
            jQuery(jQuery(this).attr('name')).hide().removeClass('hidden-tab').fadeIn(); // Show content for current tab
        }
    });

    var i;
    for (i = 1; i <= jQuery("#wp-dp-options-tab li").length; i++) {
        if (myUrlTab == myUrlTabName + i) {
            wp_dp_reset_tabs();
            jQuery("a[name='" + myUrlTab + "']").attr("id", "current"); // Activate url tab
            jQuery(myUrlTab).hide().removeClass('hidden-tab').fadeIn(); // Show url tab content        
        }
    }

    // End here
    jQuery(document).on('click', '#wrapper_boxed_layoutoptions1', function (event) {
        "use strict";
        var theme_option_layout = jQuery('#wrapper_boxed_layoutoptions1 input[name=layout_option]:checked').val();
        if (theme_option_layout == 'wrapper_boxed') {
            jQuery("#layout-background-theme-options").show();
        } else {
            jQuery("#layout-background-theme-options").hide();
        }
    });
    jQuery(document).on('click', '#wrapper_boxed_layoutoptions2', function (event) {
        "use strict";
        var theme_option_layout = jQuery('#wrapper_boxed_layoutoptions2 input[name=layout_option]:checked').val();
        if (theme_option_layout == 'wrapper_boxed') {
            jQuery("#layout-background-theme-options").show();
        } else {
            jQuery("#layout-background-theme-options").hide();

        }

    });
    /*
     * textarea header_code_indent
     */

    jQuery('textarea.header_code_indent').keydown(function (e) {
        "use strict";
        if (e.keyCode == 9) {
            var start = $(this).get(0).selectionStart;
            $(this).val($(this).val().substring(0, start) + "    " + $(this).val().substring($(this).get(0).selectionEnd));
            $(this).get(0).selectionStart = $(this).get(0).selectionEnd = start + 4;
            return false;
        }
    });
    /*
     * Toggle Function
     */

    jQuery(".hidediv").hide();
    jQuery(document).on('click', '.showdiv', function (event) {
        jQuery(this).parents("article").stop().find(".hidediv").toggle(300);
    });

    chosen_selectionbox();

});

jQuery(document).on("click", ".see-new-flags", function () {
    var _this = $(this);
    var _this_id = _this.attr('data-id');
    var _this_parent = _this.parents('.flag-listing-count');
    var flags_box = _this_parent.find('.flags-list-box');
    $('.flags-list-box').hide();
    flags_box.show();
    $.ajax({
        method: "POST",
        url: wp_dp_backend_globals.ajax_url,
        dataType: "json",
        data: {
            action: "mark_review_flags_as_seen",
            review_id: _this_id,
        },
        success: function (data) {
            var flagSeenTimeout = setInterval(function () {
                flags_box.find('ul').removeClass('new-given-flags');
                clearInterval(flagSeenTimeout);
            }, 3000);
            _this.removeClass('have-new-flags');
        },
    });
    return false;
});

jQuery(document).on("click", ".flags-list-box .close-list-box", function () {
    var flags_box = $(this).parents('.flags-list-box');
    flags_box.hide();
});

jQuery(document).on("click", ".add-review-type-opt", function () {
    var thisObj = jQuery(this);
    thisObj.append(' <span class="loader-spinner"><i class="fancy-spinner"></i></span>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: 'action=add_review_type_opt',
        success: function (response) {
            jQuery(".loader-spinner").remove();
            thisObj.closest('.wp-dp-list-wrap').find('.wp-dp-list-layout').append(response);
        }
    });
});

function listing_type_price(opt_id) {
    if (jQuery("#" + opt_id).val() == 'on') {
        jQuery(".price-settings").hide();
    } else {
        jQuery(".price-settings").show();
    }
}

function wp_dp_reset_tabs() {
    "use strict";
    jQuery("#tabbed-content > div").addClass('hidden-tab'); //Hide all content
    jQuery("#wp-dp-options-tab a").attr("id", ""); //Reset id's      
}
jQuery(document).on('click', '.user_gallery li.image.ui-sortable-handle', function () {

    var attachment_id = $(this).attr('data-attachment_id');
    var image_url = $(this).children('img').attr('src');
    $('#wp_dp_profile_image_box .thumb-secs img').attr('src', image_url);
    $('#wp_dp_profile_image').val(attachment_id);
});
jQuery(document).on('click', 'label.wp-dp-chekbox', function () {
    var checkbox = jQuery(this).find('input[type=checkbox]');

    if (checkbox.is(":checked")) {
        jQuery('#' + checkbox.attr('name')).val(checkbox.val());
        jQuery('#' + checkbox.attr('name')).attr('value', 'on');
    } else {
        jQuery('#' + checkbox.attr('name')).val('off');
        jQuery('#' + checkbox.attr('name')).attr('value', 'off');
    }
});

/*
 * upload file url
 */

jQuery(document).on('click', 'uploadfileurl', function () {
    var $ = jQuery;
    var id = $(this).attr("name");
    var custom_uploader = wp.media({
        title: 'Select File',
        button: {
            text: 'Add File'
        },
        multiple: false
    })
            .on('select', function () {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                jQuery('#' + id).val(attachment.url);
            }).open();

});
/*
 * 
 *  number of featured listings check
 */
jQuery("#wp_dp_package_fieldnumber_of_featured_listingsvalue, #wp_dp_package_fieldnumber_of_top_cat_listingsvalue").keyup(function () {
    var val = jQuery(this).val();
    var error_message = jQuery(this).attr('data-error');
    var number_of_allowed_listings = jQuery('#wp_dp_package_fieldnumber_of_listing_allowedvalue').val();
    if (parseInt(val) > parseInt(number_of_allowed_listings)) {
        alert(error_message);
        jQuery(this).val('');

    }
    var val = '';

});

/**
 * Plugin Option Saving
 *
 */
function plugin_option_save(admin_url) {
    //"use strict";
    var returnType = wp_dp_validation_process(jQuery("#plugin-options"));
    if (returnType == false) {
        return false;
    }
    jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);
    // enable disabled select fields before storing data
    var disabled = $("#plugin-options select:disabled").prop('disabled', false);
    function newValues() {
        var serializedValues = jQuery("#plugin-options input,#plugin-options select,#plugin-options textarea").serialize() + '&action=plugin_option_save';
        return serializedValues;
    }
    var serializedReturn = newValues();

    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: serializedReturn,
        success: function (response) {
            jQuery(".loading_div").hide();
            jQuery(".form-msg .innermsg").html(response);
            jQuery(".form-msg").show();
            jQuery(".outerwrapp-layer").delay(3000).fadeOut(500);
            slideout();
            // Disable disabled selects back again.
            disabled.prop('disabled', true);
        }
    });
}


/**
 * Plugin Reset Option
 *
 */
function cs_rest_plugin_options(admin_url) {
    "use strict";

    var var_confirm = confirm("You current Plugin options will be replaced with the default options.");
    if (var_confirm == true) {
        var dataString = 'action=plugin_option_rest_all';
        jQuery.ajax({
            type: "POST",
            url: admin_url,
            data: dataString,
            success: function (response) {

                jQuery(".form-msg").show();
                jQuery(".form-msg").html(response);
                jQuery(".loading_div").hide();
                window.location.reload(true);
                slideout();
            }
        });
    }
}

/*
 * reset tabs
 */
function resetTabs() {
    "use strict";
    jQuery("#tabbed-content > div").addClass('hidden-tab'); //Hide all content
    jQuery("#wp-dp-options-tab a").attr("id", ""); //Reset id's      
}
/*
 * del media
 */
function del_media(id) {
    "use strict";
    var $ = jQuery;
    jQuery('input[name="' + id + '"]').show();
    jQuery('#' + id + '_box').hide();
    jQuery('#' + id).val('');
    jQuery('#' + id).next().show();
}
/*
 * toggle with value
 */
function toggle_with_value(id) {
    "use strict";
    if (id == 0) {
        jQuery("#wrapper_repeat_event").hide();
    } else {
        jQuery("#wrapper_repeat_event").show();
    }
}

/*
 * chosen selection box
 */

function chosen_selectionbox() {
    "use strict";
    if (jQuery('.chosen-select, .chosen-select-deselect, .chosen-select-no-single, .chosen-select-no-results, .chosen-select-width').length != '') {
        var config = {
            '.chosen-select': {width: "100%"},
            '.chosen-select-deselect': {allow_single_deselect: true},
            '.chosen-select-no-single': {disable_search_threshold: 10, width: "100%"},
            '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
            '.chosen-select-width': {width: "95%"}
        }
        for (var selector in config) {
            jQuery(selector).chosen(config[selector]);
        }
    }
}

/*
 * gllsearch map
 */
function gll_search_map() {
    "use strict";
    var vals;
    vals = jQuery('#wp_dp_location_address').val();
    jQuery('.gllpSearchField').val(vals);
}
/*
 * remove image
 */
function remove_image(id) {
    "use strict";
    var $ = jQuery;
    $('#' + id).val('');
    $('#' + id + '_img_div').hide();
}
/*
 * slideout
 */
function slideout() {
    "use strict";
    setTimeout(function () {
        jQuery(".form-msg").slideUp("slow", function () {
        });
    }, 5000);
}
/*
 * div remove
 */
function wp_dp_div_remove(id) {
    "use strict";
    jQuery("#" + id).remove();
}
/*
 * wp_dp_toggle
 */
function wp_dp_toggle(id) {
    "use strict";
    jQuery("#" + id).slideToggle("slow");
}
/*
 * wp_dp_toggle_height
 */
function wp_dp_toggle_height(value, id) {
    "use strict";
    var $ = jQuery;
    if (value == "Post Slider") {
        jQuery("#post_slider" + id).show();
        jQuery("#choose_slider" + id).hide();
        jQuery("#layer_slider" + id).hide();
        jQuery("#show_post" + id).show();
    } else if (value == "Flex Slider") {
        jQuery("#choose_slider" + id).show();
        jQuery("#layer_slider" + id).hide();
        jQuery("#post_slider" + id).hide();
        jQuery("#show_post" + id).hide();
    } else if (value == "Custom Slider") {
        jQuery("#layer_slider" + id).show();
        jQuery("#choose_slider" + id).hide();
        jQuery("#post_slider" + id).hide();
        jQuery("#show_post" + id).hide();
    } else {
        jQuery("#" + id).removeClass("no-display");
        jQuery("#post_slider" + id).show();
        jQuery("#choose_slider" + id).hide();
        jQuery("#layer_slider" + id).hide();
        jQuery("#show_post" + id).hide();
    }
}
/*
 * wp_dp_toggle_list
 */
function wp_dp_toggle_list(value, id) {
    "use strict";
    var $ = jQuery;

    if (value == "custom_icon") {
        jQuery("#" + id).addClass("no-display");
        jQuery("#wp_dp_list_icon").show();
    } else {
        jQuery("#" + id).removeClass("no-display");
        jQuery("#wp_dp_list_icon").hide();
    }
}
/*
 * wp_dp_counter_image
 */
function wp_dp_counter_image(value, id) {
    "use strict";
    var $ = jQuery;

    if (value == "icon") {
        jQuery(".selected_image_type" + id).hide();
        jQuery(".selected_icon_type" + id).show();
    } else {
        jQuery(".selected_image_type" + id).show();
        jQuery(".selected_icon_type" + id).hide();
    }

}
/*
 * wp_dp_counter_view_type
 */
function wp_dp_counter_view_type(value, id) {
    "use strict";
    var $ = jQuery;

    if (value == "icon-border") {
        jQuery("#selected_view_icon_type" + id).hide();
        jQuery("#selected_view_border_type" + id).show();
        jQuery("#selected_view_icon_image_type" + id).hide();
        jQuery("#selected_view_icon_icon_type" + id).show();
    } else {
        jQuery("#selected_view_icon_type" + id).show();
        jQuery("#selected_view_border_type" + id).hide();
        jQuery("#selected_view_icon_image_type" + id).show();
    }

}
/*
 * wp_dp_icon_toggle_view
 */
function wp_dp_icon_toggle_view(value, id, object) {
    "use strict";
    var $ = jQuery;
    if (value == "bg_style") {
        jQuery("#selected_icon_view_" + id + " #label-icon").html('Icon Background Color');

    } else if (value == "border_style") {
        jQuery("#selected_icon_view_" + id + " #label-icon").html('Border Color');
    }

}
/*
 * Counter Image Show Hide End
 */

/*
 * CPricetable Title Show Hide Start
 */

function wp_dp_pricetable_style_vlaue(value, id) {
    "use strict";
    var $ = jQuery;
    if (value == "classic") {
        jQuery("#pricetbale-title" + id).hide();
    } else {
        jQuery("#pricetbale-title" + id).show();
    }
}
/*
 * show_sidebar
 */

function show_sidebar(id, random_id) {
    "use strict";
    var $ = jQuery;
    jQuery(document).on('click', 'input[class="radio_wp_dp_sidebar"]', function (event) {
        jQuery(this).parent().parent().find(".check-list").removeClass("check-list");
        jQuery(this).siblings("label").children("#check-list").addClass("check-list");
    });
    var randomeID = "#" + random_id;
    if ((id == 'left') || (id == 'right')) {
        $(randomeID + "_sidebar_right," + randomeID + "_sidebar_left").hide();
        $(randomeID + "_sidebar_" + id).show();
    } else if ((id == 'both') || (id == 'none')) {
        $(randomeID + "_sidebar_right," + randomeID + "_sidebar_left").hide();
    }
}


/*
 * wp_dp_toggle_gal
 */

function wp_dp_toggle_gal(id, counter) {
    "use strict";
    if (id == 0) {
        jQuery("#link_url" + counter).hide();
        jQuery("#video_code" + counter).hide();
    } else if (id == 1) {
        jQuery("#link_url" + counter).hide();
        jQuery("#video_code" + counter).show();
    } else if (id == 2) {
        jQuery("#link_url" + counter).show();
        jQuery("#video_code" + counter).hide();
    }
}

var _commonshortcode = (function (id) {
    "use strict";
    var mainConitem = jQuery("#" + id)
    var totalItemCon = mainConitem.find(".wp-dp-wrapp-clone").size();
    mainConitem.find(".fieldCounter").val(totalItemCon);
    mainConitem.sortable({
        cancel: '.wp-dp-clone-append .form-elements,.wp-dp-disable-true',
        placeholder: "ui-state-highlight"
    });

});
var counter_ingredient = 0;
var html_popup = "<div id='confirmOverlay' style='display:block'> \
								<div id='confirmBox'><div id='confirmText'>Are you sure to do this?</div> \
								<div id='confirmButtons'><div class='button confirm-yes'>Delete</div>\
								<div class='button confirm-no'>Cancel</div><br class='clear'></div></div></div>"


//page Section items delete start
jQuery(document).on("click", ".btndeleteitsection", function () {
    "use strict";
    jQuery(this).parents(".parentdeletesection").addClass("warning");
    jQuery(this).parent().append(html_popup);

    jQuery(document).on('click', '.confirm-yes', function (event) {
        jQuery(this).parents(".parentdeletesection").fadeOut(400, function () {
            jQuery(this).remove();
        });
        jQuery("#confirmOverlay").remove();
        count_widget--;
        if (count_widget == 0)
            jQuery("#add_page_builder_item").removeClass("hasclass");
    });
    jQuery(document).on('click', '.confirm-no', function (event) {
        jQuery(this).parents(".parentdeletesection").removeClass("warning");
        jQuery("#confirmOverlay").remove();
    });
    return false;
});


//page Builder items delete start
var category_delet = '';
jQuery(document).on("click", ".btndeleteit", function () {
    "use strict";
    jQuery(this).parents(".parentdelete").addClass("warning");
    jQuery(this).parent().append(html_popup);
    var category_delet = '';
    category_delet = jQuery(this).attr('data-catid');

    jQuery(document).on('click', '.confirm-yes', function (event) {
        var prev_parent_id = jQuery(this).closest('.parentdeletesection').attr('id');
        var prev_total_columns = jQuery('#' + prev_parent_id + ' input[name="total_column[]"]').val();
        jQuery('#' + prev_parent_id + ' input[name="total_column[]"]').val(parseInt(prev_total_columns) - parseInt(1));

        jQuery(this).parents(".parentdelete").fadeOut(400, function () {
            jQuery(this).remove();
            jQuery('input[name="deleted_categories"]').val(jQuery('input[name="deleted_categories"]').val() + ',' + category_delet);
        });

        jQuery(this).parents(".parentdelete").each(function () {
            var lengthitem = jQuery(this).parents(".dragarea").find(".parentdelete").size() - 1;
            jQuery(this).parents(".dragarea").find("input.textfld").val(lengthitem);
        });

        jQuery("#confirmOverlay").remove();
        count_widget--;
        if (count_widget == 0)
            jQuery("#add_page_builder_item").removeClass("hasclass");

    });
    jQuery(document).on('click', '.confirm-no', function (event) {
        jQuery(this).parents(".parentdelete").removeClass("warning");
        jQuery("#confirmOverlay").remove();
    });

    return false;
});

/*
 * page Builder items delete end
 */

/*
 * adding social network start
 */

function social_icon_del(id) {
    "use strict";
    jQuery("#del_" + id).remove();
    jQuery("#" + id).remove();
}

/*
 * Sidebar Layout
 */

function wp_dp_slider_element_toggle(id) {
    "use strict";
    if (id == 'default_header') {
        jQuery("#wrapper_default_header").hide();
        jQuery("#wrapper_breadcrumb_header").hide();
        jQuery("#wrapper_custom_slider").hide();
        jQuery("#wrapper_map").hide();
        jQuery("#wrapper_no-header").hide();
    } else if (id == 'custom_slider') {
        jQuery("#wrapper_custom_slider").show();
        jQuery("#wrapper_default_header").hide();
        jQuery("#wrapper_breadcrumb_header").hide();
        jQuery("#wrapper_map").hide();
        jQuery("#wrapper_no-header").hide();
    } else if (id == 'no-header') {
        jQuery("#wrapper_no-header").show();
        jQuery("#wrapper_default_header").hide();
        jQuery("#wrapper_breadcrumb_header").hide();
        jQuery("#wrapper_custom_slider").hide();
        jQuery("#wrapper_map").hide();
    } else if (id == 'breadcrumb_header') {
        jQuery("#wrapper_breadcrumb_header").show();
        jQuery("#wrapper_default_header").show();
        jQuery("#wrapper_custom_slider").hide();
        jQuery("#wrapper_map").hide();
        jQuery("#wrapper_no-header").hide();
    } else if (id == 'map') {
        jQuery("#wrapper_map").show();
        jQuery("#wrapper_default_header").hide();
        jQuery("#wrapper_breadcrumb_header").hide();
        jQuery("#wrapper_custom_slider").hide();
        jQuery("#wrapper_no-header").hide();
    } else {
        jQuery("#wrapper_default_header").hide();
        jQuery("#wrapper_breadcrumb_header").hide();
        jQuery("#wrapper_custom_slider").hide();
        jQuery("#wrapper_map").hide();
        jQuery("#wrapper_no-header").hide();
    }

}

/*
 * toggle hide/show
 */
function wp_dp_hide_show_toggle(id, div, type) {
    "use strict";
    if (type == 'theme_options') {
        if (id == 'default') {
            jQuery("#wp_dp_sh_paddingtop_range").hide();
            jQuery("#wp_dp_sh_paddingbottom_range").hide();
        } else if (id == 'custom') {
            jQuery("#wp_dp_sh_paddingtop_range").show();
            jQuery("#wp_dp_sh_paddingbottom_range").show();
        }

    } else {
        if (id == 'default') {
            jQuery("#" + div).hide();
        } else if (id == 'custom') {
            jQuery("#" + div).show();
        }
    }
}

/*
 * background options
 */

function wp_dp_section_background_settings_toggle(id, rand_no) {
    "use strict";
    if (id == "no-image") {
        jQuery(".section-custom-background-image-" + rand_no).hide();
        jQuery(".section-slider-" + rand_no).hide();
        jQuery(".section-custom-slider-" + rand_no).hide();
        jQuery(".section-background-video-" + rand_no).hide();
    } else if (id == "section-custom-background-image") {
        jQuery(".section-slider-" + rand_no).hide();
        jQuery(".section-custom-slider-" + rand_no).hide();
        jQuery(".section-background-video-" + rand_no).hide();
        jQuery(".section-custom-background-image-" + rand_no).show();
    } else if (id == "section-slider") {
        jQuery(".section-custom-background-image-" + rand_no).hide();
        jQuery(".section-slider-" + rand_no).show();
        jQuery(".section-custom-slider-" + rand_no).hide();
        jQuery(".section-background-video-" + rand_no).hide();

    } else if (id == "section-custom-slider") {
        jQuery(".section-custom-background-image-" + rand_no).hide();
        jQuery(".section-slider-" + rand_no).hide();
        jQuery(".section-custom-slider-" + rand_no).show();
        jQuery(".section-background-video-" + rand_no).hide();

    } else if (id == "section_background_video") {
        jQuery(".section-custom-background-image-" + rand_no).hide();
        jQuery(".section-slider-" + rand_no).hide();
        jQuery(".section-custom-slider-" + rand_no).hide();
        jQuery(".section-background-video-" + rand_no).show();

    } else {
        jQuery(".section-custom-background-image-" + rand_no).hide();
        jQuery(".section-slider-" + rand_no).hide();
        jQuery(".section-custom-slider-" + rand_no).hide();
        jQuery(".section-background-video-" + rand_no).hide();
    }
}


/*
 * thumbnail view
 */

function wp_dp_thumbnail_view(id) {
    "use strict";
    if (id == "none") {
        jQuery("#wrapper_thumb_slider").hide();
        jQuery("#wrapper_post_thumb_image").hide();

    } else if (id == "single") {
        jQuery("#wrapper_thumb_slider").hide();
        jQuery("#wrapper_post_thumb_image").show();
        jQuery("#wrapper_thumb_audio").hide();
    } else if (id == "slider") {
        jQuery("#wrapper_post_thumb_image").hide();
        jQuery("#wrapper_thumb_slider").show();
        jQuery("#wrapper_thumb_audio").hide();
    } else if (id == "audio") {
        jQuery("#wrapper_post_thumb_image").hide();
        jQuery("#wrapper_thumb_slider").hide();
        jQuery("#wrapper_thumb_audio").show();
    }


}
/*
 * post view
 */
function wp_dp_post_view(id) {
    "use strict";
    if (id == "single") {
        jQuery("#wrapper_post_detail, #wrapper_post_detail_slider, #wrapper_audio_view, #wrapper_video_view").hide();
        jQuery("#wrapper_post_detail").show();
    } else if (id == "audio") {
        jQuery("#wrapper_post_detail, #wrapper_post_detail_slider, #wrapper_audio_view, #wrapper_video_view").hide();
        jQuery("#wrapper_audio_view").show();
    } else if (id == "video") {
        jQuery("#wrapper_post_detail, #wrapper_post_detail_slider, #wrapper_audio_view, #wrapper_video_view").hide();
        jQuery("#wrapper_video_view").show();
    } else if (id == "slider") {
        jQuery("#wrapper_post_detail, #wrapper_post_detail_slider, #wrapper_audio_view, #wrapper_video_view").hide();
        jQuery("#wrapper_post_detail_slider").show();
    } else {
        jQuery("#wrapper_post_detail, #wrapper_post_detail_slider, #wrapper_audio_view, #wrapper_video_view").hide();
    }
}

/*
 * show slider
 */
function wp_dp_show_slider(value) {
    "use strict";
    if (value == 'Revolution Slider') {
        jQuery('#tab-sub-header-options ul,#tab-sub-header-options #wp_dp_background_img_box').hide();
        jQuery('#wp_dp_default_header_header').show();
        jQuery('#wp_dp_custom_slider_1').show();
    } else if (value == 'No sub Header') {
        jQuery('#tab-sub-header-options ul,#tab-sub-header-options #wp_dp_background_img_box').not('#tab-sub-header-options ul#wp_dp_header_border_color_color').hide();
        jQuery('#wp_dp_default_header_header,#tab-sub-header-options ul#wp_dp_header_border_color_color').show();
    } else {
        jQuery('#tab-sub-header-options ul,#tab-sub-header-options #wp_dp_background_img_box').show();
        jQuery('#wp_dp_custom_slider_1,#wp_dp_header_border_color_color').hide();
    }
}
/*
 * add field
 */

function wp_dp_add_field(id, type) {
    "use strict";
    var wrapper = jQuery("#" + id + " .input_fields_wrap"); //Fields wrapper
    var items = jQuery("#" + id + " .input_fields_wrap > div").length + 1;

    var uniqueNum = type + '_' + Math.floor(Math.random() * 99999);

    var remove = 'javascript:wp_dp_remove_field("' + uniqueNum + '","' + id + '")';

    jQuery("#" + id + "  .counter_num").val(items);

    jQuery(wrapper).append('<div class="wp-dp-wrapp-clone wp-dp-shortcode-wrapp  wp-dp-pbwp-content" id="' + uniqueNum + '"><ul class="form-elements bcevent_title"><li class="to-label"><label>Pricing Feature ' + items + '</label></li><li class="to-field"><div class="input-sec"><input class="txtfield" type="text" value="" name="pricing_feature[]"></div><div id="price_remove"><a class="remove_field" onclick=' + remove + '><i class="icon-minus-circle" style="color:#000; font-size:18px"></i></div></a></li></ul></div>'); //add input box
}
/*
 * remove field
 */

function wp_dp_remove_field(id, wrapper) {
    "use strict";
    var totalItems = jQuery("#" + wrapper + "  .counter_num").val() - 1;
    jQuery("#" + wrapper + "  .counter_num").val(totalItems);
    jQuery("#" + wrapper + " #" + id + "").remove();
}

jQuery('#tab-location-settings-wp-dp-events').bind('tabsshow', function (event, ui) {
    if (ui.panel.id == "map-tab") {
        resizeMap();
    }
});
/*
 * createclone
 */
function _createclone(object, id, section, post) {
    "use strict";

    var _this = object.closest(".column");
    _this.clone().insertAfter(_this);
    callme();
    jQuery(".draginner").sortable({
        connectWith: '.draginner',
        handle: '.column-in',
        cancel: '.draginner .poped-up,#confirmOverlay',
        revert: false,
        start: function (event, ui) {
            jQuery(ui.item).css({"width": "25%"})
        },
        receive: function (event, ui) {
            callme();
            getsorting(ui)
        },
        placeholder: "ui-state-highlight",
        forcePlaceholderSize: true
    });
    return false;
}
/*
 * aremoverlay
 */

function _removerlay(object) {
    "use strict";
    jQuery("#wp-dp-widgets-list .loader").remove();
    var _elem1 = "<div id='wp-dp-pbwp-outerlay'></div>",
            _elem2 = "<div id='wp-dp-widgets-list'></div>";
    var $elem;
    $elem = object.closest('div[class*="wp-dp-wrapp-class-"]');
    $elem.unwrap();
    $elem.unwrap();
    $elem.hide()
}
/*
 * create pop short
 */
function _createpopshort(object) {
    "use strict";
    var _structure = "<div id='wp-dp-pbwp-outerlay'><div id='wp-dp-widgets-list'></div></div>";

    var a = object.closest(".column-in").next();
    jQuery(a).wrap(_structure).delay(100).fadeIn(150);


}

// Post xml import

/*
 * Header Options
 */

// 
function wp_dp_header_option(val) {
    "use strict";
    if (val == 'none') {
        jQuery('#wrapper_rev_slider,#wrapper_headerbg_image').hide();
    } else if (val == 'wp_dp_rev_slider') {
        jQuery('#wrapper_rev_slider').fadeIn();
        jQuery('#wrapper_headerbg_image').hide();
    } else if (val == 'wp_dp_bg_image_color') {
        jQuery('#wrapper_headerbg_image').fadeIn();
        jQuery('#wrapper_rev_slider').hide();
    }
}

/*
 * banner widget toggle
 */

function wp_dp_banner_widget_toggle(view, id) {
    "use strict";
    if (view == 'random') {
        jQuery("#wp_dp_banner_style_field_" + id).show();
        jQuery("#wp_dp_banner_code_field_" + id).hide();
        jQuery("#wp_dp_banner_number_field_" + id).show();
    } else if (view == 'single') {
        jQuery("#wp_dp_banner_style_field_" + id).hide();
        jQuery("#wp_dp_banner_code_field_" + id).show();
        jQuery("#wp_dp_banner_number_field_" + id).hide();
    }
}
/**
 * add qual list
 *
 */
var counter_qual = 0;
function add_qual_list(admin_url, theme_url) {


    counter_qual++;
    var dataString = 'wp_dp_qual_name=' + jQuery("#wp_dp_qual_name").val() +
            '&wp_dp_qual_desc=' + jQuery("#wp_dp_qual_desc").val() +
            '&action=add_qual_to_list';
    jQuery(".feature-loader").html("<i class='fancy-spinner'></i>");
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery("#total_quals").append(response);
            jQuery(".feature-loader").html("");
            removeoverlay('add_qual_title', 'append');
            jQuery("#wp_dp_qual_name").val("Title");
            jQuery("#wp_dp_qual_desc").val("");
        }
    });
    return false;
}
/**
 * schedule list
 *
 */
var counter_schedule = 0;
function add_schedule_list(admin_url, theme_url) {

    counter_schedule++;
    var dataString = 'wp_dp_schedule_name=' + jQuery("#wp_dp_schedule_name").val() +
            '&wp_dp_schedule_time=' + jQuery("#wp_dp_schedule_time").val() +
            '&wp_dp_schedule_desc=' + jQuery("#wp_dp_schedule_desc").val() +
            '&action=add_schedule_to_list';
    jQuery(".feature-loader").html("<i class='fancy-spinner'></i>");
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery("#total_schedules").append(response);
            jQuery(".feature-loader").html("");
            removeoverlay('add_schedule_title', 'append');
            jQuery("#wp_dp_schedule_name").val("Title");
            jQuery("#wp_dp_schedule_time").val("");
            jQuery("#wp_dp_schedule_desc").val("");
        }
    });
    return false;
}
/**
 * camp sched list
 *
 */
var counter_camp_sched = 0;
function add_camp_sched_list(admin_url, theme_url) {

    counter_camp_sched++;
    var dataString = 'wp_dp_camp_sched_name=' + jQuery("#wp_dp_camp_sched_name").val() +
            '&wp_dp_camp_sched_time=' + jQuery("#wp_dp_camp_sched_time").val() +
            '&wp_dp_camp_sched_loc=' + jQuery("#wp_dp_camp_sched_loc").val() +
            '&wp_dp_camp_sched_desc=' + jQuery("#wp_dp_camp_sched_desc").val() +
            '&action=add_camp_sched_to_list';
    jQuery(".feature-loader").html("<i class='fancy-spinner'></i>");
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery("#total_camp_scheds").append(response);
            jQuery(".feature-loader").html("");
            removeoverlay('add_camp_sched_title', 'append');
            jQuery("#wp_dp_camp_sched_name").val("Title");
            jQuery("#wp_dp_camp_sched_time").val("");
            jQuery("#wp_dp_camp_sched_loc").val("");
            jQuery("#wp_dp_camp_sched_desc").val("");
        }
    });
    return false;
}

//send smtp mail
function send_smtp_mail(admin_url) {
    "use strict";
    jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);
    var serializedValues = jQuery("#plugin-options input,#plugin-options select,#plugin-options textarea,#plugin-options checkbox").serialize() + '&action=send_smtp_mail';

    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: serializedValues,
        success: function (response) {
            jQuery(".loading_div").hide();
            jQuery(".form-msg .innermsg").html(response);
            jQuery(".form-msg").show();
            jQuery(".outerwrapp-layer").fadeTo(2000, 1000).slideUp(1000);
            slideout();
        }
    });
}
function use_smtp_mail_opt(thisObj) {
    "use strict";
    var opt_id = jQuery(thisObj).data('id');
    if (jQuery("#" + opt_id).val() == 'on') {
        jQuery("#wp-dp-no-smtp-div").show();
    } else {
        jQuery("#wp-dp-no-smtp-div").hide();
    }
}

function use_wooC_gateways(thisObj) {
    "use strict";
    var opt_id = jQuery(thisObj).data('id');
    if (jQuery("#" + opt_id).val() == 'on') {
        jQuery("#wp-dp-no-wooC-gateway-div").hide();
    } else {
        jQuery("#wp-dp-no-wooC-gateway-div").show();
    }
}
/*
 * chosen selection box
 */


/*
 * Custom Fields for Listing Type
 */
function wp_dp_custom_fields_js() {
    "use strict";
    var parentItem = jQuery("#wp-dp-pb-formelements");
    parentItem.sortable({
        cancel: 'div div.poped-up,.pb-toggle',
        handle: ".pbwp-legend",
        placeholder: "ui-state-highlighter"
    });
}

jQuery(document).on('click', 'img.pbwp-clone-field', function () {
    var _this = jQuery(this),
            b = _this.closest('div.pbwp-clone-field');
    var dataString = b.clone().html();
    var counter = $("pbwp-clone-field").length;
    var dataResponse = dataString.replace(/wp_dp_cus_field_dropdown_options_imgs/g, 'wp_dp_cus_field_dropdown_options_imgs' + counter + 1);
    jQuery('<div class="pbwp-clone-field clearfix">' + dataResponse + '</div>').insertAfter(b);
    var a = _this.parents('.pbwp-form-sub-fields').find('input:radio');
    a.each(function (index, el) {
        jQuery(this).val(index + 1);
    });
});

jQuery(document).on('click', 'img.pbwp-dpove-field', function () {
    jQuery(this).parent('.pbwp-clone-field').remove();
});

jQuery(document).on('click', 'a.pbwp-toggle', function () {
    jQuery(this).parents(".pbwp-legend").next().slideToggle(300);
});

jQuery(document).on('click', '.pbwp-dpove', function () {
    var a = confirm("This will delete Item");
    if (a) {
        jQuery(this).parents(".pb-item-container").remove();
    }
});

/*
 * Custom Fields for Listing Type
 */
function wp_dp_custom_fields_form_builder_js() {
    "use strict";
    var parentItem = jQuery("#wp-dp-pb-formelements_form_builder");
    parentItem.sortable({
        cancel: 'div div.poped-up,.pb-toggle',
        handle: ".pbwp-legend",
        placeholder: "ui-state-highlighter"
    });
    var c = 0;
    parentItem.on("click", "img.pbwp-clone-field", function (e) {
        e.preventDefault();
        var _this = jQuery(this),
                b = _this.closest('div.pbwp-clone-field');
        var dataString = b.clone().html();
        var counter = $("pbwp-clone-field").length;
        var dataResponse = dataString.replace(/wp_dp_cus_field_dropdown_options_imgs/g, 'wp_dp_cus_field_dropdown_options_imgs' + counter + 1);
        jQuery('<div class="pbwp-clone-field clearfix">' + dataResponse + '</div>').insertAfter(b);
        var a = _this.parents('.pbwp-form-sub-fields').find('input:radio');
        a.each(function (index, el) {
            jQuery(this).val(index + 1);
        });

    });

    parentItem.on("click", "img.pbwp-dpove-field", function (e) {
        jQuery(this).parent('.pbwp-clone-field').remove();
    });
    parentItem.on("click", ".pbwp-dpove", function (e) {
        e.preventDefault();
        var a = confirm("This will delete Item");
        if (a) {
            jQuery(this).parents(".pb-item-container").remove();
        }
    });
    parentItem.on("click", "a.pbwp-toggle", function (e) {
        e.preventDefault();
        jQuery(this).parents(".pbwp-legend").next().slideToggle(300);
    });
}


function wp_dp_createpop(data, type) {
    "use strict";
    var _structure = "<div id='wp-dp-pbwp-outerlay'><div id='wp-dp-widgets-list'></div></div>",
            $elem = jQuery('#wp-dp-widgets-list');
    jQuery('body').addClass("wp-dp-overflow");
    if (type == "csmedia") {
        $elem.append(data);
    }
    if (type == "filter") {
        jQuery('#' + data).wrap(_structure).delay(100).fadeIn(150);
        jQuery('#' + data).parent().addClass("wide-width");
    }
    if (type == "filterdrag") {
        jQuery('#' + data).wrap(_structure).delay(100).fadeIn(150);
    }

    if (jQuery('.chosen-select, .chosen-select-deselect, .chosen-select-no-single, .chosen-select-no-results, .chosen-select-width').length != '') {
        var config = {
            '.chosen-select': {width: "100%"},
            '.chosen-select-deselect': {allow_single_deselect: true},
            '.chosen-select-no-single': {disable_search_threshold: 10, width: "100%"},
            '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
            '.chosen-select-width': {width: "95%"}
        }
        for (var selector in config) {
            jQuery(selector).chosen(config[selector]);
        }
    }

}

function add_listing_feature(admin_url) {
    "use strict";
    var dataString = 'wp_dp_feature_name=' + jQuery("#wp_dp_feature_name").val() + '&wp_dp_feature_icon=' + jQuery("#e9_element_feature_icon").val() + '&action=add_feature_to_list';
    jQuery(".feature-loader").html("<i class='fancy-spinner'></i>");
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery("#total_features").append(response);
            jQuery(".feature-loader").html("");
            wp_dp_removeoverlay('add_feature_title', 'append');
            jQuery("#wp_dp_feature_name").val("Title");
        }
    });
    return false;
}

function change_tag_value(changeID, changeValue) {
    jQuery('#' + changeID).html(changeValue);
}

function add_listing_category(admin_url) {
    "use strict";
    var dataString = 'wp_dp_category_name=' + jQuery("#wp_dp_category_name").val() + '&wp_dp_category_parent=' + jQuery("#wp_dp_category_parent").val() + '&wp_dp_listing_taxonomy_icons=' + jQuery("#e9_element_listing_type_icons").val() + '&action=add_category_to_list';
    jQuery(".category-loader").html("<i class='fancy-spinner'></i>");
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery("#total_categories").append(response);
            jQuery(".category-loader").html("");
            wp_dp_removeoverlay('add_category_title', 'append');
            jQuery("#wp_dp_category_name").val("Title");
        }
    });
    return false;
}

function wp_dp_removeoverlay(id, text) {
    "use strict";
    jQuery("#wp-dp-widgets-list .loader").remove();
    var _elem1 = "<div id='wp-dp-pbwp-outerlay'></div>",
            _elem2 = "<div id='wp-dp-widgets-list'></div>",
            $elem = jQuery("#" + id);
    jQuery("#wp-dp-widgets-list").unwrap();
    if (text == "append" || text == "filterdrag") {
        $elem.hide().unwrap();
    }
    if (text == "widgetitem") {
        $elem.hide().unwrap();
        jQuery("body").append("<div id='wp-dp-pbwp-outerlay'><div id='wp-dp-widgets-list'></div></div>");
        return false;

    }
    if (text == "ajax-drag") {
        jQuery("#wp-dp-widgets-list").remove();
    }
    jQuery("body").removeClass("wp-dp-overflow");
}

function wp_dp_check_fields_avail() {
    "use strict";
    jQuery('input[id^="check_field_name"]').change(function (e) {
        var wp_dp_ajaxurl = jQuery('#tabbed-content').data('ajax-url');
        var doneTypingInterval = 1000; //time in ms, 5 second for example
        var name = jQuery(this).val();
        var serializedValues = jQuery("form").serialize();
        var $this = jQuery(this);
        var dataString = 'name=' + name +
                '&form_field_names=' + serializedValues +
                '&action=wp_dp_check_fields_avail'

        setTimeout(function () {

            $this.next('span').html('<i class="fancy-spinner"></i>');
            jQuery.ajax({
                type: "POST",
                url: wp_dp_ajaxurl,
                data: dataString,
                dataType: 'json',
                success: function (response) {
                    if (response.type == 'success') {
                        $this.next('.name-checking').html(response.message);
                        jQuery('input[type="button"]').removeAttr('disabled');
                    } else if (response.type == 'error') {
                        $this.next('.name-checking').html(response.message);
                        jQuery('input[type="button"]').attr('disabled', 'disabled');
                    }
                }
            });
        }, doneTypingInterval)

    });
}

jQuery(document).on('change', '.dir_meta_key_field', function (e) {
    "use strict";
    var wp_dp_ajaxurl = jQuery('#tabbed-content').data('ajax-url');
    var doneTypingInterval = 1000; //time in ms, 5 second for example
    var name = jQuery(this).val();
    var serializedValues = jQuery("form").serialize();
    var $this = jQuery(this);
    var dataString = 'name=' + name +
            '&form_field_names=' + serializedValues +
            '&action=wp_dp_check_fields_avail'

    $this.next('span').html('<i class="fancy-spinner"></i>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_ajaxurl,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            if (response.type == 'success') {
                $this.next('.name-checking').html('<em class="name-check-pass">' + response.message + '</em>');
                $this.parents('.pb-item-container').find('.pbwp-legend').removeClass('item-field-error');
                $this.removeClass('meta-field-error');
            } else if (response.type == 'error') {
                $this.addClass('admin-field-error');
                $this.addClass('meta-field-error');
                $this.next('.name-checking').html('<em class="name-check-error">' + response.message + '</em>');
                $this.parents('.pb-item-container').find('.pbwp-legend').addClass('item-field-error');
            }
        }
    });

});


jQuery(document).on('change', '.dir-res-meta-key-field', function (e) {
    "use strict";
    var wp_dp_ajaxurl = jQuery('#tabbed-content').data('ajax-url');
    var doneTypingInterval = 1000; //time in ms, 5 second for example
    var name = jQuery(this).val();
    var serializedValues = jQuery("form").serialize();
    var $this = jQuery(this);
    var dataString = 'name=' + name +
            '&form_field_names=' + serializedValues +
            '&action=wp_dp_check_reservation_fields_avail'

    $this.next('span').html('<i class="fancy-spinner"></i>');
    jQuery('input[type="submit"]').attr('disabled', 'disabled');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_ajaxurl,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            if (response.type == 'success') {
                $this.next('.name-checking').html('<em class="name-check-pass">' + response.message + '</em>');
                jQuery('input[type="submit"]').removeAttr('disabled');
                $this.parents('.pb-item-container').find('.pbwp-legend').removeClass('item-field-error');
            } else if (response.type == 'error') {
                $this.next('.name-checking').html('<em class="name-check-error">' + response.message + '</em>');
                $this.parents('.pb-item-container').find('.pbwp-legend').addClass('item-field-error');
            }
        }
    });

});

function wp_dp_listing_type_change(slug, post_id) {
    "use strict";

    var wp_dp_ajaxurl = jQuery('#tabbed-content').data('ajax-url');
    var dataString = 'listing_type_slug=' + slug + '&post_id=' + post_id + '&action=listing_type_dyn_fields';
    jQuery('#wp-dp-listing-type-field').html('<div class="wp-dp-fields-loader"><i class="fancy-spinner"></i></div>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_ajaxurl,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            if (typeof response.listing_fields !== 'undefined') {
                jQuery('#wp-dp-listing-type-field').html(response.listing_fields);
                if (typeof response.detail_options !== 'undefined') {
                    if (response.detail_options.yelp_places == 'on') {
                        jQuery('#wp-dp-listing-yelp-holder').show();
                    } else {
                        jQuery('#wp-dp-listing-yelp-holder').hide();
                    }
                    if (response.detail_options.floor_plans == 'on') {
                        jQuery('#wp-dp-listing-floor-plan-holder').show();
                    } else {
                        jQuery('#wp-dp-listing-floor-plan-holder').hide();
                    }
                    if (response.detail_options.appartments == 'on') {
                        jQuery('#wp-dp-listing-appartment-holder').show();
                    } else {
                        jQuery('#wp-dp-listing-appartment-holder').hide();
                    }
                    if (response.detail_options.attachments == 'on') {
                        jQuery('#wp-dp-listing-attachments-holder').show();
                    } else {
                        jQuery('#wp-dp-listing-attachments-holder').hide();
                    }
                    if (response.detail_options.features == 'on') {
                        jQuery('#wp-dp-listing-features-holder').show();
                        jQuery('#wp-dp-listing-features-btn-holder').show();
                    } else {
                        jQuery('#wp-dp-listing-features-holder').hide();
                        jQuery('#wp-dp-listing-features-btn-holder').hide();
                    }
                    if (response.detail_options.video == 'on') {
                        jQuery('#wp-dp-listing-video-holder').show();
                        jQuery('#wp-dp-listing-type-video-con').show();
                    } else {
                        jQuery('#wp-dp-listing-video-holder').hide();
                        jQuery('#wp-dp-listing-type-video-con').hide();
                    }
                    if (response.detail_options.virtual_tour == 'on') {
                        jQuery('#wp-dp-listing-type-virtual-tour-con').show();
                    } else {
                        jQuery('#wp-dp-listing-type-virtual-tour-con').hide();
                    }
                    if (response.detail_options.faqs == 'on') {
                        jQuery('#listing-types-faqs-side-tab').show();
                    } else {
                        jQuery('#listing-types-faqs-side-tab').hide();
                    }
                }
            } else {
                jQuery('#wp-dp-listing-type-field').html('Error');
            }
        }
    });
}

function change_feature_value(changeID, changeValue) {
    "use strict";
    jQuery('#' + changeID).html(changeValue);
}


function wp_dp_show_user_profile_data(user_ID) {
    "use strict";
    var dataString = 'user_profile_id=' + user_ID + '&security=' + wp_dp_backend_globals.security + '&action=wp_dp_posted_by_user_data';
    jQuery('#posted_by_user_data_fields').html('<div class="wp-dp-fields-loader"><i class="fancy-spinner"></i></div>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: dataString,
        success: function (response) {
            jQuery('#posted_by_user_data_fields').html(response);
        }
    });
}

jQuery(function ($) {
    "use strict";
    // Product gallery file uploads
    var gallery_frame;

    jQuery('.add_gallery_plugin').on('click', 'input', function (event) {

        var $el = $(this);
        var rand_id = jQuery('.add_gallery_plugin').attr('data-rand_id');
        var button_label = $el.data('button_label');
        var multiple = $el.data('multiple');
        var wp_dp_var_theme_url = $("#wp_dp_var_theme_url").val();
        var $gallery_images = $('#gallery_container_' + rand_id + ' ul.gallery_images');
        var wp_dp_var_gallery_id = $('#gallery_container_' + rand_id).data("csid");
        event.preventDefault();

        if (button_label !== '') {
            button_label = button_label;
        } else {
            button_label = 'Add Gallery Image';
        }
        if (multiple == false) {
            multiple = false;
        } else {
            multiple = true;
        }

        // Create the media frame.
        gallery_frame = wp.media({
            title: "Select Image",
            multiple: multiple,
            library: {type: 'image'},
            button: {text: button_label}
        });


        // When an image is selected, run a callback.
        gallery_frame.on('select', function () {
            var selection = gallery_frame.state().get('selection');

            selection.map(function (attachment) {

                attachment = attachment.toJSON();
                if (attachment.type == 'image') {
                    var gallery_url = attachment.url;
                    var gallery_ID = attachment.id;
                }

                if (attachment.url) {
                    var attachment_ids = Math.floor((Math.random() * 965674) + 1);
                    if (multiple == false) {
                        var listItems = jQuery('#gallery_container_' + rand_id + ' ul.gallery_images').children();
                        var count = listItems.length;
                        if (count > 0) {
                            $('#gallery_container_' + rand_id + ' ul.gallery_images img').attr('src', gallery_url);
                            $('#gallery_container_' + rand_id + ' ul.gallery_images input[name="' + wp_dp_var_gallery_id + '"]').val(gallery_ID);
                        } else {
                            $('#gallery_container_' + rand_id + ' ul.gallery_images').append('\
                            <li class="image" data-attachment_id="' + attachment_ids + '">\
                                <img src="' + gallery_url + '" />\
                                <input type="hidden" value="' + gallery_ID + '" name="' + wp_dp_var_gallery_id + '" />\
                                <div class="actions">\
                                    <span><a href="javascript:;" class="delete" title="' + $el.data('delete') + '"><i class="icon-cross"></i></a></span>\
                                </div>\
                            </li>');
                        }
                    } else {
                        $('#gallery_container_' + rand_id + ' ul.gallery_images').append('\
                            <li class="image" data-attachment_id="' + attachment_ids + '">\
                                <img src="' + gallery_url + '" />\
                                <input type="hidden" value="' + gallery_ID + '" name="' + wp_dp_var_gallery_id + '_ids[]" />\
                                <div class="actions">\
                                    <span><a href="javascript:;" class="delete" title="' + $el.data('delete') + '"><i class="icon-cross"></i></a></span>\
                                </div>\
                            </li>');
                    }
                }

            });
            jQuery('#' + wp_dp_var_gallery_id + '_temp').html('');
        });

        // Finally, open the modal.
        gallery_frame.open();
    });
});

/*
 * Gallery Number of Items
 */
function gal_num_of_items(id, rand_id, numb) {
    "use strict";
    var wp_dp_var_gal_count = 0;
    jQuery("#gallery_sortable_" + rand_id + " > li").each(function (index) {
        wp_dp_var_gal_count++;
        jQuery('input[name="wp_dp_' + id + '_num"]').val(wp_dp_var_gal_count);
    });

    if (numb != '') {
        var wp_dp_var_data_temp = jQuery('#wp_dp_var_' + id + '_temp');
        if (jQuery('input[name="wp_dp_' + id + '_num"]').val() == numb) {
            wp_dp_var_data_temp.html('<input type="hidden" name="wp_dp_' + id + '_ids[]" value="">');
        }
    }
}


jQuery(".remove_field").on("click", function () {
    "use strict";
    var repeater_id = jQuery(this).data('id');
    jQuery(this).parent("#" + repeater_id).remove();
});

jQuery(".floor_plans_repeater_btn").on('click', function () {
    "use strict";
    var repeater_id = jQuery(this).data('id') + '_fields';
    var dataString = 'action=wp_dp_floor_plans_repeating_fields&die=true&ajax=true';
    jQuery(this).append(' <span class="loader-spinner"><i class="fancy-spinner"></i></span>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: dataString,
        success: function (response) {
            jQuery('#' + repeater_id).append(response);
            jQuery('.loader-spinner').remove();
        }
    });
});

jQuery("#floor_plans_repeater_fields").sortable({});

jQuery(".attachments_repeater_btn").on('click', function () {
    "use strict";
    var repeater_id = jQuery(this).data('id') + '_fields';
    var listing_type_id = jQuery(this).attr('listing_type_id');
    var dataString = 'action=wp_dp_files_attachments_repeating_fields&die=true&ajax=true&listing_type_id=' + listing_type_id;
    jQuery(this).append(' <span class="loader-spinner"><i class="fancy-spinner"></i></span>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: dataString,
        success: function (response) {
            jQuery('#' + repeater_id).append(response);
            jQuery('.loader-spinner').remove();
        }
    });
});

jQuery("#attachments_repeater_fields").sortable({});


jQuery(".near_by_repeater_btn").on('click', function () {
    "use strict";
    var repeater_id = jQuery(this).data('id') + '_fields';
    var loading_div = jQuery(this).data('id') + '_loader';
    var dataString = 'action=wp_dp_near_by_repeating_fields&die=true&ajax=true';
    jQuery('#' + loading_div).html('<div class="wp-dp-fields-loader"><i class="fancy-spinner"></i></div>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: dataString,
        success: function (response) {
            jQuery('#' + loading_div).html('');
            jQuery('#' + repeater_id).append(response);
        }
    });
});

jQuery("#near_by_repeater_fields").sortable({});

/*
 * apartment for sale
 */

jQuery(".apartment_repeater_btn").on('click', function () {
    "use strict";
    var repeater_id = jQuery(this).data('id') + '_fields';
    var dataString = 'action=wp_dp_apartment_repeating_fields&die=true&ajax=true';
    jQuery(this).append(' <span class="loader-spinner"><i class="fancy-spinner"></i></span>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: dataString,
        success: function (response) {
            jQuery('.loader-spinner').remove();
            jQuery('#' + repeater_id).append(response);
        }
    });
});

jQuery("#apartment_repeater_fields").sortable({});



/*
 * 
 * environmental responsibility 
 */

jQuery(".env_res_repeater_btn").on('click', function () {
    "use strict";
    var repeater_id = jQuery(this).data('id') + '_fields';
    var loading_div = jQuery(this).data('id') + '_loader';
    var dataString = 'action=wp_dp_env_res_repeating_fields&die=true&ajax=true';
    jQuery('#' + loading_div).html('<div class="wp-dp-fields-loader"><i class="fancy-spinner"></i></div>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: dataString,
        success: function (response) {
            jQuery('#' + loading_div).html('');
            jQuery('#' + repeater_id).append(response);
        }
    });
});

jQuery("#env_res_repeater_fields").sortable({});


/*
 * 
 * end environmental responsibility
 */

jQuery('.wp_dp_calendar').calendar({
    startYear: new Date().getFullYear(),
    allowOverlap: true,
    displayWeekNumber: false,
    displayDisabledDataSource: false,
    displayHeader: true,
    alwaysHalfDay: false,
    dataSource: [], // an array of data
    style: 'border',
    enableRangeSelection: false,
    disabledDays: [],
    disabledWeekDays: [],
    hiddenWeekDays: [],
    roundRangeLimits: false,
    contextMenuItems: [], // an array of menu items,
    customDayRenderer: null,
    customDataSourceRenderer: null,
    // Callback Events
    clickDay: add_calendar_date,
    daycontextMenu: null,
    selectRange: null,
    renderEnd: null,
});


jQuery(".wp_dp_calendar_fields input").each(function (index) {
    var dateVal = new Date(jQuery(this).val());
    jQuery(".wp_dp_calendar").calendar({
        dataSource: [
            {
                id: 0,
                name: "Google I/O",
                location: "San Francisco, CA",
                startDate: dateVal,
                endDate: dateVal
            },
        ], // an array of data
    });
});

function add_calendar_date(date_obj) {
    "use strict";
    var date_added = date_obj.date.getFullYear() + '-' + (date_obj.date.getMonth() + parseInt(1)) + '-' + date_obj.date.getDate();
    var dateHTML = '<input type="hidden" name="wp_dp_calendar[]" id="date-' + date_added + '" value="' + date_added + '">';
    if (jQuery("#date-" + date_added).length > 0) {
        jQuery("#date-" + date_added).remove();
    } else {
        jQuery(".wp_dp_calendar_fields").append(dateHTML);
    }
}

jQuery(".day-content").on("click", function () {
    jQuery(this).parent('.day').removeAttr('style');
    jQuery(this).parent('.day').toggleClass('active');

});

function wp_dp_pl_opt_backup_generate(admin_url) {
    "use strict";
    jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);

    var dataString = 'action=wp_dp_pl_opt_backup_generate';
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery(".loading_div").hide();
            jQuery(".form-msg .innermsg").html(response);
            jQuery(".form-msg").show();
            jQuery(".outerwrapp-layer").delay(100).fadeOut(100);
            window.location.reload(true);
            slideout();
        }
    });
    //return false;
}

function wp_dp_set_p_filename(file_value, file_path) {
    "use strict";
    jQuery(".backup_action_btns").find('input[type="button"]').attr('data-file', file_value);
    jQuery(".backup_action_btns").find('> a').attr('href', file_path + file_value);
    jQuery(".backup_action_btns").find('> a').attr('download', file_value);
}

jQuery('.backup_generates_area').on('click', '#cs-p-backup-restore, #cs-p-backup-url-restore', function () {
    "use strict";

    jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);

    var admin_url = jQuery('.backup_generates_area').data('ajaxurl');
    var file_name = jQuery(this).data('file');

    var dataString = 'file_name=' + file_name + '&action=wp_dp_pl_backup_file_restore';

    if (typeof (file_name) === 'undefined') {

        var file_name = jQuery('#bkup_import_url').val();

        var dataString = 'file_name=' + file_name + '&file_path=yes&action=wp_dp_pl_backup_file_restore';
    }

    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {

            jQuery(".loading_div").hide();
            jQuery(".form-msg .innermsg").html(response);
            jQuery(".form-msg").show();
            jQuery(".outerwrapp-layer").delay(2000).fadeOut(100);


            window.location.reload(true);
            slideout();
        }
    });
    //return false;
});

(function ($) {
    $(function () {
        /*
         * Delete Backup Options.
         */
        $('.backup_generates_area').on('click', '#cs-p-backup-delte', function () {
            "use strict";

            var var_confirm = window.confirm(wp_dp_backend_globals.delete_selected_file_cofirmation);
            if (var_confirm == true) {
                $(".outerwrapp-layer,.loading_div").fadeIn(100);

                var admin_url = $('.backup_generates_area').data('ajaxurl');
                var file_name = $(this).data('file');

                var dataString = 'file_name=' + file_name + '&action=delete_options_backup_file';
                $.ajax({
                    type: "POST",
                    url: admin_url,
                    data: dataString,
                    success: function (response) {

                        $(".loading_div").hide();
                        $(".form-msg .innermsg").html(response);
                        $(".form-msg").show();
                        $(".outerwrapp-layer").delay(2000).fadeOut(100);
                        window.location.reload(true);
                        slideout();
                    }
                });
                //return false;
            }
        });
    });
})(jQuery);

(function ($) {
    $(function () {
        /*
         * Delete Backup locations.
         */
        $('.backup_locations_generates_area').on('click', '#btn_delete_locations_backup', function () {
            "use strict";
            var var_confirm = window.confirm(wp_dp_backend_globals.delete_selected_file_cofirmation);
            if (var_confirm == true) {
                $(".outerwrapp-layer,.loading_div").fadeIn(100);

                var admin_url = $('.backup_generates_area').data('ajaxurl');
                var file_name = $(this).data('file');

                var dataString = 'file_name=' + file_name + '&action=delete_locations_backup_file';
                $.ajax({
                    type: "POST",
                    url: admin_url,
                    data: dataString,
                    success: function (response) {

                        $(".loading_div").hide();
                        $(".form-msg .innermsg").html(response);
                        $(".form-msg").show();
                        $(".outerwrapp-layer").delay(2000).fadeOut(100);
                        window.location.reload(true);
                        slideout();
                    }
                });
                //return false;
            }
        });

        /*
         * Restore or Import locations.
         */
        $('.backup_locations_generates_area').on('click', '#btn_import_locations_from_url', function () {

            "use strict";
            $(".outerwrapp-layer,.loading_div").fadeIn(100);
            var admin_url = $('.backup_generates_area').data('ajaxurl');

            //  var file_name = $("#choose_file")[0].files[0].name;
            //var dataString = 'file_name=' + file_name + '&action=restore_locations_backup';
            //   if (typeof (file_name) === 'undefined') {
            //   var file_name = $('#bkup_locations_import_url').val();
            //  var dataString = 'file_name=' + file_name + '&file_path=yes&action=restore_locations_backup';
            //}

            var get_file = document.getElementById('choose_file').files[0];
            var dataString = new FormData();
            dataString.append('file_name', get_file);
            dataString.append('action', 'restore_locations_backup');

            $.ajax({
                type: "POST",
                url: admin_url,
                data: dataString,
                processData: false,
                contentType: false,
                success: function (response) {
                    $(".loading_div").hide();
                    $(".form-msg .innermsg").html(response);
                    $(".form-msg").show();
                    $(".outerwrapp-layer").delay(2000).fadeOut(100);
                  //  window.location.reload(true);
                  //  slideout();
                }
            });
        });
        
        
        $('.backup_locations_generates_area').on('click', '#btn_restore_locations_backup', function () {

            "use strict";
            $(".outerwrapp-layer,.loading_div").fadeIn(100);

            var admin_url = $('.backup_generates_area').data('ajaxurl');
            var file_name = $(this).data('file');

            var dataString = 'file_name=' + file_name + '&action=restore_locations_backup';

            if (typeof (file_name) === 'undefined') {

                var file_name = $('#bkup_locations_import_url').val();

                var dataString = 'file_name=' + file_name + '&file_path=yes&action=restore_locations_backup';
            }

            $.ajax({
                type: "POST",
                url: admin_url,
                data: dataString,
                success: function (response) {
                    $(".loading_div").hide();
                    $(".form-msg .innermsg").html(response);
                    $(".form-msg").show();
                    $(".outerwrapp-layer").delay(2000).fadeOut(100);
                    window.location.reload(true);
                    slideout();
                }
            });
        });
        
        

        /*
         * Delete Listing type categories backup.
         */
        $('.backup_listing_type_categories_generates_area').on('click', '#btn_delete_listing_type_categories_backup', function () {
            "use strict";
            var var_confirm = window.confirm(wp_dp_backend_globals.delete_selected_file_cofirmation);
            if (var_confirm == true) {
                $(".outerwrapp-layer,.loading_div").fadeIn(100);

                var admin_url = $('.backup_generates_area').data('ajaxurl');
                var file_name = $(this).data('file');

                var dataString = 'file_name=' + file_name + '&action=delete_listing_type_categories_backup_file';
                $.ajax({
                    type: "POST",
                    url: admin_url,
                    data: dataString,
                    success: function (response) {

                        $(".loading_div").hide();
                        $(".form-msg .innermsg").html(response);
                        $(".form-msg").show();
                        $(".outerwrapp-layer").delay(2000).fadeOut(100);
                        window.location.reload(true);
                        slideout();
                    }
                });
                //return false;
            }
        });

        /*
         * Restore or Import listing type categories.
         */
        $('.backup_listing_type_categories_generates_area').on('click', '#btn_restore_listing_type_categories_backup, #btn_import_listing_type_categories_from_url', function () {
            "use strict";
            $(".outerwrapp-layer,.loading_div").fadeIn(100);

            var admin_url = $('.backup_generates_area').data('ajaxurl');
            var file_name = $(this).data('file');

            var dataString = 'file_name=' + file_name + '&action=restore_listing_type_categories_backup';

            if (typeof (file_name) === 'undefined') {

                var file_name = $('#bkup_listing_type_categories_import_url').val();

                var dataString = 'file_name=' + file_name + '&file_path=yes&action=restore_listing_type_categories_backup';
            }

            $.ajax({
                type: "POST",
                url: admin_url,
                data: dataString,
                success: function (response) {
                    $(".loading_div").hide();
                    $(".form-msg .innermsg").html(response);
                    $(".form-msg").show();
                    $(".outerwrapp-layer").delay(2000).fadeOut(100);
                    window.location.reload(true);
                    slideout();
                }
            });
        });
    });
})(jQuery);


function generate_locations_backup(admin_url) {
    "use strict";
    jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);

    var dataString = 'action=generate_locations_backup';
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery(".loading_div").hide();
            jQuery(".form-msg .innermsg").html(response);
            jQuery(".form-msg").show();
            jQuery(".outerwrapp-layer").delay(100).fadeOut(100);
            window.location.reload(true);
            slideout();
        }
    });
    //return false;
}

function wp_dp_custom_fields_script(id) {
    "use strict";
    var parentItem = jQuery("#" + id);
    parentItem.sortable({
        cancel: 'div div.poped-up,.pb-toggle',
        handle: ".pbwp-legend",
        placeholder: "ui-state-highlighter"
    });
    var c = 0;
    parentItem.on("click", "img.pbwp-clone-field", function (e) {
        e.preventDefault();
        var _this = jQuery(this),
                b = _this.closest('div.pbwp-clone-field');
        b.clone().insertAfter(b);
        var a = _this.parents('.pbwp-form-sub-fields').find('input:radio');
        a.each(function (index, el) {
            jQuery(this).val(index + 1);
        });
    });
    parentItem.on("click", "img.pbwp-dpove-field", function (e) {
        e.preventDefault();
        var _this = jQuery(this),
                b = _this.closest('.pbwp-form-sub-fields');
        c = b.find('div.pbwp-clone-field').length;
        if (c > 1) {
            _this.closest("div.pbwp-clone-field").remove()
        }
        _this.parents('div.pbwp-clone-field').remove();
    });
    parentItem.on("click", ".pbwp-dpove", function (e) {
        e.preventDefault();
        var a = confirm("This will delete Item");
        if (a) {
            jQuery(this).parents(".pb-item-container").remove()
            alertbox();
        }
    })

    parentItem.on("click", "a.pbwp-toggle", function (e) {
    });
}

function opening_hour_time_lapse(thisObj, divID) {
    "use strict";
    var inputID = jQuery(thisObj).attr('name');
    var value = jQuery('#' + inputID).val();
    if (value == 'on') {
        jQuery(divID).show();
    } else {
        jQuery(divID).hide();
    }
}

jQuery(document).on('click', 'label.cs-chekbox', function () {
    "use strict";
    var checkbox = jQuery(this).find('input[type=checkbox]');

    if (checkbox.is(":checked")) {
        jQuery(this).find('input[type="hidden"]').val(checkbox.val());
        jQuery(this).find('input[type="hidden"]').attr('value', 'on');
    } else {
        jQuery(this).find('input[type="hidden"]').val('off');
        jQuery(this).find('#input[type="hidden"]').attr('value', 'off');
    }
});

jQuery(document).on("click", ".wp-dp-uploadMedia", function () {
    var $ = jQuery;
    var id = $(this).attr("name");
    var custom_uploader = wp.media({
        title: 'Select File',
        button: {
            text: 'Add File'
        },
        multiple: false
    })
            .on('select', function () {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                jQuery('#' + id).val(attachment.id);
                jQuery('#' + id).next().hide();
                jQuery('#' + id + '_img').attr('src', attachment.url);
                jQuery('#' + id + '_box').show();
            }).open();
});

/*
 * Category Delete
 */

jQuery(document).on("click", ".delete-category", function () {
    jQuery(this).parents(".parentdelete").addClass("warning");
    jQuery(this).parent().append(html_popup);

    jQuery(document).on('click', '.confirm-yes', function (event) {
        jQuery(this).parents(".parentdelete").fadeOut(800, function () {
            jQuery(this).remove();
        });
        jQuery("#confirmOverlay").remove();
    });
    jQuery(document).on('click', '.confirm-no', function (event) {
        jQuery(this).parents(".parentdelete").removeClass("warning");
        jQuery("#confirmOverlay").remove();
    });

    return false;
});

function add_package_field(admin_url) {
    "use strict";
    var default_label = jQuery('#wp_dp_field_label').attr('title');
    var dataString = 'wp_dp_field_label=' + jQuery('#wp_dp_field_label').val() + '&wp_dp_field_type=' + jQuery('#wp_dp_field_type').val() + '&action=add_package_field';
    jQuery('.wp-dp-package-button').append(' <span class="loader-spinner"><i class="fancy-spinner"></i></span>');
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery('.wp-dp-packages-list-wrap .wp-dp-list-layout').append(response);
            wp_dp_removeoverlay('add_field_title', 'append');
            jQuery(".loader-spinner").remove();
        }
    });
    return false;
}

jQuery(document).on("click", ".package-field-delete", function () {
    jQuery(this).parents(".parentdelete").addClass("warning");
    jQuery(this).parent().append(html_popup);

    jQuery(document).on('click', '.confirm-yes', function (event) {
        jQuery(this).parents(".parentdelete").fadeOut(800, function () {
            jQuery(this).remove();
        });
        jQuery("#confirmOverlay").remove();
    });
    jQuery(document).on('click', '.confirm-no', function (event) {
        jQuery(this).parents(".parentdelete").removeClass("warning");
        jQuery("#confirmOverlay").remove();
    });

    return false;
});

/**
 * search map
 */
function wp_dp_gl_search_map() {
    "use strict";
    var vals;
    vals = jQuery('#loc_address').val();
    jQuery('.gllpSearchField').val(vals);
}


jQuery('#wp_dp_listing_type_icon_image').change(function () {
    var val = jQuery("select#wp_dp_listing_type_icon_image option").filter(":selected").val();
    if (val == 'image') {
        jQuery('#listing-type-icon-holder').hide();
        jQuery('#listing-type-image-holder').show(500);
    } else {
        jQuery('#listing-type-image-holder').hide();
        jQuery('#listing-type-icon-holder').show(500);
    }
});

jQuery('#wp_dp_listing_menu_type_icon_image').change(function () {
    var val = jQuery("select#wp_dp_listing_menu_type_icon_image option").filter(":selected").val();
    if (val == 'image') {
        jQuery('#listing-menu-type-icon-holder1').hide();
        jQuery('#listing-menu-type-image-holder1').show(500);
    } else {
        jQuery('#listing-menu-type-image-holder1').hide();
        jQuery('#listing-menu-type-icon-holder1').show(500);
    }
});


function set_locations_backup_filename(file_value, file_path) {
    "use strict";
    jQuery(".backup_locations_generates_area .backup_action_btns").find('input[type="button"]').attr('data-file', file_value);
    jQuery(".backup_locations_generates_area .backup_action_btns").find('> a').attr('href', file_path + file_value);
    jQuery(".backup_locations_generates_area .backup_action_btns").find('> a').attr('download', file_value);
}

function generate_locations_backup(admin_url) {
    "use strict";
    jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);

    var dataString = 'action=generate_locations_backup';
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery(".loading_div").hide();
            jQuery(".form-msg .innermsg").html(response);
            jQuery(".form-msg").show();
            jQuery(".outerwrapp-layer").delay(100).fadeOut(100);
            slideout();
        }
    });
}

function set_listing_type_categories_backup_filename(file_value, file_path) {
    "use strict";
    jQuery(".backup_listing_type_categories_generates_area .backup_action_btns").find('input[type="button"]').attr('data-file', file_value);
    jQuery(".backup_listing_type_categories_generates_area .backup_action_btns").find('> a').attr('href', file_path + file_value);
    jQuery(".backup_listing_type_categories_generates_area .backup_action_btns").find('> a').attr('download', file_value);
}

function generate_listing_type_categories_backup(admin_url) {
    "use strict";
    jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);

    var dataString = 'action=generate_listing_type_categories_backup';
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            jQuery(".loading_div").hide();
            jQuery(".form-msg .innermsg").html(response);
            jQuery(".form-msg").show();
            jQuery(".outerwrapp-layer").delay(100).fadeOut(100);
            window.location.reload(true);
            slideout();
        }
    });
}

function wp_dp_ft_icon_feature(id) {//begin function
    "use strict";
    var getting_icon;

    var ajax_url = wp_dp_pt_vars.ajax_url;

    var this_loader = $('#icon-' + id);
    this_loader.html('<div class="loader-holder" style="width:18px;"><img src="' + wp_dp_pt_vars.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></div>');
    getting_icon = $.ajax({
        url: ajax_url,
        method: "POST",
        data: {
            field: 'icon',
            action: 'wp_dp_ft_iconpicker'
        },
        dataType: "json"
    }).done(function (response) {
        if (typeof response.icon !== 'undefined') {
            this_loader.html(response.icon);
        }
        chosen_selectionbox();
    }).fail(function () {
        this_loader.html('');
    });

}//end function

function wp_dp_show_company_users(value, ajax_url, plugin_url) {
    "use strict";
    var selecting_users,
            this_loader = $('#listing_user_member_col');
    this_loader.html('<div class="loader-holder"><img src="' + plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></div>');
    selecting_users = $.ajax({
        url: ajax_url,
        method: "POST",
        data: 'company=' + value + '&action=wp_dp_listing_back_members',
        dataType: "json"
    }).done(function (response) {
        if (typeof response.html !== 'undefined') {
            this_loader.html(response.html);
        }
        chosen_selectionbox();
    }).fail(function () {
        this_loader.html('');
    });
}

/*
 * Company Name based on Profile Type
 */

jQuery(document).on("change", ".member_profile_type", function () {
    "use strict";
    var current_val = jQuery(this).val();
    if (current_val == 'company') {
        jQuery(".member_company_name").show();
    } else {
        jQuery(".member_company_name").hide();
    }
});

/*
 * Profile Type based on User Type
 */

jQuery(document).on("change", ".member_user_type", function () {
    "use strict";
    var current_val = jQuery(this).val();
    if (current_val == 'reseller') {
        jQuery(".member-profile-types").show();
    } else {
        jQuery(".member-profile-types").hide();
    }
});

/*
 *  getting startd button hide show fields
 */

function wp_dp_getting_startrd() {
    if (jQuery("#wp_dp_header_buton_switch").val() == 'on') {
        jQuery("#header_button_title").show();
        jQuery("#header_button_url").show();
        jQuery("#wp_dp_head_btn").show();
    } else {
        jQuery("#wp_dp_head_btn").hide();
        jQuery("#header_button_title").hide();
        jQuery("#header_button_url").hide();
    }
}

/*
 * Social Auto Post ( plugin settings ) message format hide show..
 */
function wp_dp_autopost_twitter_hide_show(opt_id) {
    if (jQuery("#" + opt_id).val() != 'on') {
        jQuery("#twitter_message_format").hide();
    } else {
        jQuery("#twitter_message_format").show();
    }
}
function wp_dp_autopost_facebook_hide_show(opt_id) {
    if (jQuery("#" + opt_id).val() != 'on') {
        jQuery("#facebook_message_format").hide();
    } else {
        jQuery("#facebook_message_format").show();
    }
}

function wp_dp_autopost_linkedin_hide_show(opt_id) {
    if (jQuery("#" + opt_id).val() != 'on') {
        jQuery("#linkedin_message_format").hide();
    } else {
        jQuery("#linkedin_message_format").show();
    }
}
/*
 * End Social Auto Post ...
 */

var counter_banner = 0;
function  wp_dp_banner_add_banner(admin_url) {
    "use strict";
    counter_banner++;
    var image_path = jQuery('#wp_dp_banner_field_image_rand').val();

    var banner_title_input = jQuery("#banner_title_input").val();
    var banner_style_input = jQuery("#banner_style_input").val();
    var banner_type_input = jQuery("#banner_type_input").val();
    var banner_field_url_input = jQuery("#banner_field_url_input").val();
    var banner_target_input = jQuery("#banner_target_input").val();
    var adsense_code_input = jQuery("#adsense_code_input").val();


    if (banner_type_input == 'image') {
        if (typeof image_path == 'undefined' || image_path == '') {
            alert(wp_dp_backend_globals.banner_image_error);
            return false;
        }
    }

    if (banner_type_input == 'code') {
        if (typeof adsense_code_input == 'undefined' || adsense_code_input == '') {
            alert(wp_dp_backend_globals.banner_code_error);
            return false;
        }
    }

    jQuery(".add-banner-button").append(' <span class="loader-spinner"><i class="fancy-spinner"></i></span>');

    if (banner_style_input != "") {

        var dataString = 'image_path=' + image_path +
                '&banner_title_input=' + banner_title_input +
                '&banner_style_input=' + banner_style_input +
                '&banner_type_input=' + banner_type_input +
                '&banner_field_url_input=' + banner_field_url_input +
                '&banner_target_input=' + banner_target_input +
                '&counter_banner=' + counter_banner +
                '&adsense_code_input=' + adsense_code_input +
                '&action=wp_dp_banner_ads_banner';
        jQuery.ajax({
            type: "POST",
            url: admin_url,
            data: dataString,
            success: function (response) {
                jQuery(".banners-list-wrap-area .wp-dp-list-layout").append(response);
                jQuery(".social-area").show(200);
                jQuery("#wp_dp_banner_field_image_rand,#banner_title_input,#banner_field_url_input,#adsense_code_input").val("");
                jQuery("#wp_dp_banner_field_image_rand_box").find('img').attr('src', '');
                jQuery("#banner_type_input").val("image");
                jQuery("#banner_style_input").val(banner_style_input);
                jQuery("#wp_dp_banner_field_image_rand_box").hide();//use this to hide image box and display only Browse button for adding next banner.
                jQuery('.loader-spinner').remove();
            }
        });
    }
}

function wp_dp_banner_toggle(id) {
    jQuery("#" + id).slideToggle("slow");
}

function wp_dp_banner_type_toggle(type, id) {
    if (type == 'image') {
        jQuery("#ads_image" + id).show();
        jQuery("#ads_code" + id).hide();
    } else if (type == 'code') {
        jQuery("#ads_image" + id).hide();
        jQuery("#ads_code" + id).show();
    }
}


/*
 * Validations
 */

jQuery("form").on("submit", function () {
    "use strict";
    var returnType = wp_dp_validation_process(jQuery(this), false);
    if (returnType == false) {
        return false;
    }
});

/*
 * Validation Process by Form
 */
function wp_dp_validation_process(form_name, display_popup) {
    var has_empty = false;
    var alert_messages = new Array();
    var field_empty = new Array();
    var object_array = new Array();
    jQuery(form_name).find('.wp-dp-dev-req-field-admin,.wp-dp-number-field,.wp-dp-email-field,.wp-dp-url-field,.wp-dp-date-field,.wp-dp-range-field').each(function (index_no) {
        var is_visible = true;
        var thisObj = jQuery(this);
        var visible_id = thisObj.data('visible');
        field_empty[index_no] = false;
        /*
         * Remove validation from tab
         */

        var tab_id = thisObj.closest('.wp_dp_tab_block').attr('id');
        if (wp_dp_is_field(tab_id) == true) {
            jQuery('a[name="#' + tab_id + '"]').removeClass('wp_dp_tab_error');
        }

        if (wp_dp_is_field(visible_id) == true) {
            is_visible = jQuery("#" + visible_id).is(':hidden');
            if (jQuery("#" + visible_id).css('display') !== 'none') {
                is_visible = true;
            } else {
                is_visible = false;
            }
        }
        if (thisObj.attr('type') == 'checkbox') {
            thisObj = jQuery("#" + thisObj.attr('name'));
            if (thisObj.val() == 'off') {
                thisObj.val('');
            }
        }
        if (!thisObj.val() && is_visible == true) {
            if (thisObj.hasClass('wp-dp-dev-req-field-admin')) {
                array_length = alert_messages.length;
                alert_messages[array_length] = wp_dp_insert_error_message(thisObj, alert_messages, 'is required!');
                object_array[array_length] = thisObj;
                has_empty = true;
                field_empty[index_no] = true;
            }
        } else {
            if (is_visible == true) {
                has_empty = wp_dp_check_field_type(thisObj, alert_messages, has_empty);
                array_length = alert_messages.length;
                field_empty[index_no] = wp_dp_check_field_type(thisObj, alert_messages, field_empty[index_no]);
                if (field_empty[index_no] == true) {
                    object_array[array_length] = thisObj;
                }
                if (thisObj.hasClass('meta-field-error') == true) {
                    object_array[array_length] = thisObj;
                    alert_messages[array_length] = 'Meta key is not valid';
                    field_empty[index_no] = true;
                    has_empty = true;
                }
            }
        }
        if (field_empty[index_no] == true) {
            if (thisObj.is(':visible') == false) {
                thisObj.closest(jQuery('.pbwp-form-holder[style="display:none;"]')).css('display', 'block');
                thisObj.closest(jQuery('.pbwp-form-holder[style="display: none;"]')).css('display', 'block');
            }
        }

        if (field_empty[index_no] == false) {
            thisObj.next('.chosen-container').removeClass('admin-field-error');
            thisObj.next('.wp-dp-dev-req-field-admin').next('.pbwp-box').removeClass('admin-field-error');
            thisObj.removeClass('admin-field-error');
        }

    });
    if (has_empty) {
        array_length = alert_messages.length;
        error_data = '<h3>Please fill out below fields correctly before submitting form.</h3><ul class="wp-dp-form-validations">';
        for (i = 0; i <= array_length; i++) {
            var thisObject = object_array[i];
            if (wp_dp_is_field(thisObject) == true) {
                var tab_id = thisObject.closest('.wp_dp_tab_block').attr('id');
                if (wp_dp_is_field(tab_id) == true) {
                    jQuery('a[name="#' + tab_id + '"]').addClass('wp_dp_tab_error');
                }
            }
            error_data += '<li>' + alert_messages[i] + '</li>';
        }
        error_data += '</ul>';
        jQuery(".wp-dp-error-messages").html('<h4>Please ensure that all required fields are completed and formatted correctly.</h4>');
        jQuery(".wp-dp-error-messages").show();

        setTimeout(function () {
            jQuery(".wp-dp-error-messages").hide();
        }, 5000);
        return false;
    }
}

/*
 * Check if field exists and not empty
 */

function wp_dp_is_field(field_value) {
    if (field_value != 'undefined' && field_value != undefined && field_value != '') {
        return true;
    } else {
        return false;
    }
}

/*
 * Check if Provided data for field is valid
 */

function wp_dp_check_field_type(thisObj, alert_messages, has_empty) {
    "use strict";
    /*
     * Check for Email Field
     */
    if (thisObj.hasClass('wp-dp-email-field')) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        if (!pattern.test(thisObj.val())) {
            array_length = alert_messages.length;
            alert_messages[array_length] = wp_dp_insert_error_message(thisObj, alert_messages, 'is not valid Email!');
            has_empty = true;
        }
    }

    /*
     * Check for Number Field
     */

    if (thisObj.hasClass('wp-dp-number-field')) {
        var pattern = /[0-9 -()+]+$/;
        if (!pattern.test(thisObj.val())) {
            array_length = alert_messages.length;
            alert_messages[array_length] = wp_dp_insert_error_message(thisObj, alert_messages, 'is not valid Number!');
            has_empty = true;
        }
    }

    /*
     * Check for URL Field
     */

    if (thisObj.hasClass('wp-dp-url-field')) {
        var pattern = /^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/;
        if (!pattern.test(thisObj.val())) {
            array_length = alert_messages.length;
            alert_messages[array_length] = wp_dp_insert_error_message(thisObj, alert_messages, 'is not valid URL!');
            has_empty = true;
        }
    }

    /*
     * Check for Date Field
     */

    if (thisObj.hasClass('wp-dp-date-field')) {
        var pattern = /^\d{2}.\d{2}.\d{4}$/;
        if (!pattern.test(thisObj.val())) {
            array_length = alert_messages.length;
            alert_messages[array_length] = wp_dp_insert_error_message(thisObj, alert_messages, 'is not valid Date!');
            has_empty = true;
        }
    }

    /*
     * Check for Meta Field
     */

    if (thisObj.hasClass('dir_meta_key_field')) {
        if (thisObj.val().indexOf(' ') > -1) {
            array_length = alert_messages.length;
            alert_messages[array_length] = wp_dp_insert_error_message(thisObj, alert_messages, 'is not valid Meta!');
            has_empty = true;
        }
    }

    /*
     * Check for Range Field
     */

    if (thisObj.hasClass('wp-dp-range-field')) {
        var min_val = thisObj.data('min');
        var max_val = thisObj.data('max');
        if (!(thisObj.val() >= min_val) || !(thisObj.val() <= max_val)) {
            array_length = alert_messages.length;
            alert_messages[array_length] = wp_dp_insert_error_message(thisObj, alert_messages, 'is not in Range! ( ' + min_val + ' - ' + max_val + ' )');
            has_empty = true;
        }
    }
    return has_empty;
}

/*
 * Making list of errors
 */

function wp_dp_insert_error_message(thisObj, alert_messages, error_msg) {
    "use strict";
    var tab_title = thisObj.closest('.wp_dp_tab_block').data('title');
    if (wp_dp_is_field(tab_title) == true) {
        tab_title = tab_title + ' : ';
    } else {
        tab_title = '';
    }
    thisObj.addClass('admin-field-error');
    if (thisObj.is('select')) {
        thisObj.next('.chosen-container').addClass('admin-field-error');
    }
    if (thisObj.is(':hidden')) {
        thisObj.next('.wp-dp-dev-req-field-admin').next('.pbwp-box').addClass('admin-field-error');
    }
    var field_label = thisObj.closest('.form-elements').children('div').children('label').html();
    return '<strong>' + tab_title + '</strong>' + field_label + ' field ' + error_msg;
}




/*
 * upload file location
 */

$('#btn_import_file').click(function (event) {
    "use strict";

var get_file = document.getElementById('btn_browse_locations_file').files[0];


    var form_data = new FormData();
    
         form_data.append('file_name', get_file);

    
    form_data.append('action', 'wp_dp_uploading_import_file');
    $.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: form_data,
        contentType: false,
        processData: false,
        success: function (response) {
            var file_url = response;
            var dataString = 'file_name=' + file_url + '&file_path=yes&action=restore_locations_backup';
            $.ajax({
                type: "POST",
                url: wp_dp_backend_globals.ajax_url,
                data: dataString,
                success: function (response) {
                    $(".loading_div").hide();
                    $(".form-msg .innermsg").html(response);
                    $(".form-msg").show();
                    $(".outerwrapp-layer").delay(2000).fadeOut(100);
                    window.location.reload(true);
                    slideout();
                }
            });

        }

    });
});
/*
 * end upload file location
 */


/*
 * upload category file
 */

$('#btn_import_cat_file').click(function (event) {
    "use strict";
    var form_data = new FormData(jQuery("#wp_dp_import_categort_form")[0]);
    form_data.append('action', 'wp_dp_uploading_import_cat_file');
    $.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: form_data,
        contentType: false,
        processData: false,
        success: function (response) {
            var file_url = response;
            var dataString = 'file_name=' + file_url + '&file_path=yes&action=restore_listing_type_categories_backup';
            $.ajax({
                type: "POST",
                url: wp_dp_backend_globals.ajax_url,
                data: dataString,
                success: function (response) {
                    $(".loading_div").hide();
                    $(".form-msg .innermsg").html(response);
                    $(".form-msg").show();
                    $(".outerwrapp-layer").delay(2000).fadeOut(100);
                    window.location.reload(true);
                    slideout();
                }
            });

        }

    });
});
/*
 * end upload category file
 */

jQuery(document).on("change", "#wp_dp_notifications_box", function () {
    "use strict";
    var value = jQuery(this).val();
    if (value == 'yes') {
        jQuery(".draw_on_map_url_field").show();
    } else {
        jQuery(".draw_on_map_url_field").hide();
    }
});


//jQuery(document).ready(function() {
//    jQuery(".pkg-price .price-table-pkg-price").keypress(function(event) {
//      return wp_dp_is_number_validation(event, this);
//    });
//});
//
//function wp_dp_is_number_validation(evt, element) {
//    var charCode = (evt.which) ? evt.which : event.keyCode
//    if (
//        (charCode != 45 || jQuery(element).val().indexOf('-') != -1) &&      // �-� CHECK MINUS, AND ONLY ONE.
//        (charCode != 46 || jQuery(element).val().indexOf('.') != -1) &&      // �.� CHECK DOT, AND ONLY ONE.
//        (charCode < 48 || charCode > 57))
//        return false;
//
//    return true;
//}

jQuery(document).ready(function () {
    jQuery('[data-toggle="tooltip"]').tooltip();
});

jQuery(document).on("click", ".add-currencies-opt", function () {
    var currency_counter = jQuery("li.wp-dp-list-item").size();
    var thisObj = jQuery(this);
    thisObj.append(' <span class="loader-spinner"><i class="fancy-spinner"></i></span>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: 'currency_counter=' + currency_counter + '&action=add_currencies_opt',
        success: function (response) {
            jQuery('.loader-spinner').remove();
            thisObj.closest('.wp-dp-list-wrap').find('.wp-dp-list-layout').append(response);
        }
    });
});

jQuery(document).on("click", ".wp-dp-parent-li-dpove", function () {
    var parent_obj = jQuery(this).closest('li');
    parent_obj.slideUp(400, function () {
        parent_obj.remove();
    });
});

jQuery(document).on("click", ".wp-dp-parent-li-edit", function () {
    var parent_obj = jQuery(this).closest('li');
    var editObj = parent_obj.find('.parent-li-edit-div');
    editObj.slideToggle(400, function () {
    });
});

function wp_dp_enquiry_status_change_bk(status_value, enquiry_id) {
    "use strict";
    var selecting_status;
    var this_loader = $('#status-loader-' + enquiry_id);

    this_loader.html('<span class="loader-holder"><img src="' + wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
    selecting_status = $.ajax({
        url: wp_dp_backend_globals.ajax_url,
        method: "POST",
        data: 'status_val=' + status_value + '&enquiry_id=' + enquiry_id + '&action=wp_dp_enquiry_status_change_bk',
        dataType: "json"
    }).done(function (response) {
        this_loader.html(response.msg);
    }).fail(function () {
        this_loader.html('');
    });
}

function wp_dp_viewing_status_change_bk(status_value, viewing_id) {
    "use strict";
    var selecting_status;
    var this_loader = $('#status-loader-' + viewing_id);

    this_loader.html('<span class="loader-holder"><img src="' + wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
    selecting_status = $.ajax({
        url: wp_dp_backend_globals.ajax_url,
        method: "POST",
        data: 'status_val=' + status_value + '&viewing_id=' + viewing_id + '&action=wp_dp_viewing_status_change_bk',
        dataType: "json"
    }).done(function (response) {
        this_loader.html(response.msg);
    }).fail(function () {
        this_loader.html('');
    });
}

jQuery(document).on("click", ".wp-dp-element-dpove", function () {
    var parent_obj = jQuery(this).closest('.wp-dp-repeater-form');
    parent_obj.slideUp(400, function () {
        parent_obj.remove();
    });
});

function wp_dp_load_all_pages(field_id, args) {
    jQuery('.loader-' + field_id).html("<img src='" + wp_dp_backend_globals.plugin_url + "/assets/backend/images/ajax-loader.gif' />").show();
    var args = jQuery('.args_' + field_id).text();
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: 'action=wp_dp_load_all_pages&args=' + args + '&field_id=' + field_id,
        dataType: "json",
        success: function (response) {
            if (typeof response.html !== 'undefined') {
                jQuery('#' + field_id + '_holder').html('');
                jQuery('#' + field_id + '_holder').html(response.html);
                jQuery('.loader-' + field_id).html('').hide();
                setTimeout(function () {
                    jQuery('#' + field_id).trigger('chosen:open');
                }, 5);
            }
        }
    });
}

function wp_dp_load_all_members(field_class, selected_memebr) {
    jQuery('.' + field_class + ' .members-loader').html("<img src='" + wp_dp_backend_globals.plugin_url + "/assets/backend/images/ajax-loader.gif' />").show();
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: 'action=wp_dp_load_all_members&selected_member=' + selected_memebr,
        dataType: "json",
        success: function (response) {
            if (typeof response.html !== 'undefined') {
                jQuery('.' + field_class).prop("onclick", null);
                jQuery('.' + field_class).html('');
                jQuery('.' + field_class).html(response.html);
                jQuery('.' + field_class + ' .members-loader').html('').hide();
                setTimeout(function () {
                    jQuery('.' + field_class + ' #wp_dp_listing_member').trigger('chosen:open');
                }, 5);
            }
        }
    });
}


function wp_dp_load_dropdown_values(field_class, field_id, action) {
    jQuery('.' + field_class + ' .select-loader').html("<img src='" + wp_dp_backend_globals.plugin_url + "/assets/backend/images/ajax-loader.gif' />").show();
    var selected_val = jQuery('#wp_dp_' + field_id).val();
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: 'action=' + action + '&selected_val=' + selected_val,
        dataType: "json",
        success: function (response) {
            if (typeof response.html !== 'undefined') {
                jQuery('.' + field_class).prop("onclick", null);
                jQuery('.' + field_class).html('');
                jQuery('.' + field_class).html(response.html);
                jQuery('.' + field_class + ' .select-loader').html('').hide();
                setTimeout(function () {
                    jQuery('.' + field_class + ' #wp_dp_' + field_id).trigger('chosen:open');
                }, 5);
            }
        }
    });
}

$(document).on('click', '.nearby-sel-img-icon-btn', function () {
    var imge_txt = $(this).attr('data-img-txt'),
            icon_txt = $(this).attr('data-icon-txt'),
            this_counter = $(this).attr('data-id'),
            this_parent = $(this).parents('.nearby-img-icon-pare'),
            img_con = this_parent.find('.nearby-img-f'),
            icon_con = this_parent.find('.nearby-icon-f'),
            hiden_field = $('#wp_dp_nearby_map_icon_type_' + this_counter);

    if (img_con.is(":visible")) {
        img_con.hide();
        icon_con.show();
        $(this).html(imge_txt);
        hiden_field.val('icon');
    } else {
        img_con.show();
        icon_con.hide();
        $(this).html(icon_txt);
        hiden_field.val('image');
    }
});
jQuery(document).ready(function ($) {
    jQuery("#profile-page .button-primary").click(function () {
        var user_company = jQuery(".user-company #wp_dp_company").val();
        if (user_company == '') {
            jQuery('.user-company .compnay-error').show();
            jQuery(".user-company #wp_dp_company_chosen").css("border", "1px solid red");
            jQuery(".user-company #wp_dp_company_chosen").css("border-radius", "6px");
            setTimeout(function () {
                jQuery('.user-company .compnay-error').hide();
                jQuery(".user-company #wp_dp_company_chosen").css("border", "");
                jQuery(".user-company #wp_dp_company_chosen").css("border-radius", "");
            }, 5000);
            return false;

        } else {
            jQuery('.user-company .compnay-error').hide();
            jQuery(".user-company #wp_dp_company_chosen").css("border", "");
            jQuery(".user-company #wp_dp_company_chosen").css("border-radius", "");
            return true;
        }
    });
});

//function wp_dp_listing_type_select_view(val) {
//    jQuery('#detail_detail_view1_fields').hide();
//    jQuery('#detail_detail_view2_fields').hide();
//    jQuery('#detail_detail_view3_fields').hide();
//    jQuery('#detail_detail_view4_fields').hide();
//    jQuery('#detail_detail_view5_fields').hide();
//    if (val == '' || typeof val === 'undefined') {
//        jQuery('#detail_detail_view1_fields').show();
//    } else {
//        jQuery('#detail_' + val + '_fields').show();
//    }
//}


jQuery(document).ready(function ($) {

    "use strict";
    $('[data-toggle="popover"]').popover();
    popup_view_box();
    /*
     * CS meta fileds Tabs
     */
    var externalLink = false;
    var myUrl = window.location.href; //get URL
    var myUrlTab = myUrl.substring(myUrl.indexOf("#")); // For localhost/tabs.html#tab2, myUrlTab = #tab2     
    var myUrlTabName = myUrlTab.substring(0, 4); // For the above example, myUrlTabName = #tab
    jQuery("#tabbed-content > div").addClass('hidden-tab'); // Initially hide all content #####EDITED#####
    jQuery("#cs-options-tab li:first a").attr("id", "current"); // Activate first tab
    jQuery("#tabbed-content > div:first").hide().removeClass('hidden-tab').fadeIn(); // Show first tab content   #####EDITED#####

    jQuery("#cs-options-tab > li:first").addClass('active');

    jQuery(document).on("click", "#cs-options-tab li > a", function (e) {

        e.preventDefault();

        if (jQuery(this).hasClass('cs-options-tab-ext')) {

            externalLink = true;
            window.location.href = jQuery(this).data('url');

        }
        if (jQuery(this).attr("id") == "current") { //detection for current tab

            return

        } else {

            wp_dp_cs_reset_tabs_plugin(externalLink);

            jQuery("#cs-options-tab > li").removeClass('active')

            jQuery(this).attr("id", "current"); // Activate this

            jQuery(this).parents('li').addClass('active');

            jQuery(jQuery(this).attr('name')).hide().removeClass('hidden-tab').fadeIn(); // Show content for current tab

        }




    });

    var i;

    for (i = 1; i <= jQuery("#cs-options-tab li").length; i++) {

        if (myUrlTab == myUrlTabName + i) {

            wp_dp_cs_reset_tabs_plugin(externalLink);

            jQuery("a[name='" + myUrlTab + "']").attr("id", "current"); // Activate url tab

            jQuery(myUrlTab).hide().removeClass('hidden-tab').fadeIn(); // Show url tab content        
        }
    }
    /*
     * End CS meta fileds Tabs
     */
});

function wp_dp_cs_reset_tabs_plugin(externalLink) {
    "use strict";
    if (externalLink !== true) {
        jQuery("#tabbed-content > div").addClass('hidden-tab'); //Hide all content
        jQuery("#cs-options-tab a").attr("id", ""); //Reset id's    
    }

}

jQuery(document).on('click', '.ziparchive-missing-link', function (e) {
    jQuery(".ziparchive-missing").show();
    e.preventDefault();
});

function popup_over() {
    jQuery('[data-toggle="popover"]').popover({trigger: "hover", placement: "right"});
}


var default_button_loader = jQuery(".wp-dp-button-loader").html();
/*
 * Loader Show Function backend
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
 * Hide Button loader Backend
 */

function wp_dp_hide_button_loader(processing_div) {
    "use strict";
    if (processing_div != "undefined" && processing_div != "" && processing_div != undefined) {
        jQuery(processing_div).removeClass("wp-dp-processing");
    }
    jQuery(".wp-dp-button-loader").hide();
    jQuery(".wp-dp-button-loader").html(default_button_loader);
}

function wp_dp_listing_type_change_filter(selected_listing, selected_category) {
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: 'action=wp_dp_load_categories_by_listing_type&selected_listing=' + selected_listing + '&selected_category=' + selected_category,
        dataType: "json",
        success: function (response) {
            if (response.type == 'success') {
                jQuery('#listing_categories_filter').html('');
                jQuery('#listing_categories_filter').html(response.html);
            }
        }
    });
}

/*
 * Show all listings
 */
function wp_dp_load_all_listings(field_class, selected_listing) {
    jQuery('.' + field_class + ' .members-loader').html("<img src='" + wp_dp_backend_globals.plugin_url + "/assets/backend/images/ajax-loader.gif' />").show();
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: 'action=dp_load_all_listings&selected_listing=' + selected_listing,
        dataType: "json",
        success: function (response) {
            if (typeof response.html !== 'undefined') {
                jQuery('.' + field_class).prop("onclick", null);
                jQuery('.' + field_class).html('');
                jQuery('.' + field_class).html(response.html);
                jQuery('.' + field_class + ' .members-loader').html('').hide();
                setTimeout(function () {
                    jQuery('.' + field_class + ' #wp_dp_select_listings').trigger('chosen:open');
                }, 5);
            }
        }
    });
}


jQuery(document).on("click", ".add-promotions-opt", function () {
    var currency_counter = jQuery("li.wp-dp-list-item").size();
    var thisObj = jQuery(this);
    thisObj.append(' <span class="loader-spinner"><i class="fancy-spinner"></i></span>');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_backend_globals.ajax_url,
        data: 'currency_counter=' + currency_counter + '&action=add_promotions_opt',
        success: function (response) {
            jQuery('.loader-spinner').remove();
            thisObj.closest('.wp-dp-list-wrap').find('.wp-dp-list-layout').append(response);
        }
    });
});


/* Time Open Close Function Start */
jQuery(".time-list #close-btn2").click(function () {
    jQuery(".time-list .open-close-time").addClass('opening-time');
});
jQuery(".time-list #close-btn1").click(function () {
    jQuery(".time-list .open-close-time").removeClass('opening-time');
});

jQuery(document).on('click', 'a[id^="listing-dev-open-time"]', function () {
    var _this_id = jQuery(this).data('id'),
            _this_day = jQuery(this).data('day'),
            _this_con = jQuery('#open-close-con-' + _this_day + '-' + _this_id),
            _this_status = jQuery('#listing-dev-open-day-' + _this_day + '-' + _this_id);
    if (typeof _this_id !== 'undefined' && typeof _this_day !== 'undefined') {
        _this_status.val('on');
        _this_con.addClass('opening-time');
    }
});

jQuery(document).on('click', 'a[id^="listing-dev-close-time"]', function () {
    var _this_id = jQuery(this).data('id'),
            _this_day = jQuery(this).data('day'),
            _this_con = jQuery('#open-close-con-' + _this_day + '-' + _this_id),
            _this_status = jQuery('#listing-dev-open-day-' + _this_day + '-' + _this_id);
    if (typeof _this_id !== 'undefined' && typeof _this_day !== 'undefined') {
        _this_status.val('');
        _this_con.removeClass('opening-time');
    }
});


$(document).on("click", ".book-btn", function () {
    "use strict";
    $(this).next(".calendar-holder").slideToggle("fast");
});

$(document).on("click", 'a[id^="wp-dp-dev-day-off-dp-"]', function () {
    "use strict";
    var _this_id = $(this).data("id");
    $("#day-dpove-" + _this_id).remove();
});

var counter = 0;
$(document).on("click", ".wp-dp-dev-insert-off-days-backend .wp-dp-dev-calendar-days .day a", function () {
    "use strict";
    if (counter == 0) {
        counter = 1;
        var adding_off_day, _this_ = $(this),
                _this_id = $(this).parents(".wp-dp-dev-insert-off-days-backend").data("id"),
                _day = $(this).data("day"),
                _month = $(this).data("month"),
                _year = $(this).data("year"),
                _adding_date = _year + "-" + _month + "-" + _day,
                _add_date = true,
                _this_append = $("#wp-dp-dev-add-off-day-app-" + _this_id),
                no_off_day_msg = _this_append.find("#no-book-day-" + _this_id),
                this_loader = $("#dev-off-day-loader-" + _this_id),
                this_act_msg = $("#wp-dp-dev-act-msg-" + _this_id);
        _this_append.find("li").each(function () {
            var date_field = $(this).find('input[name^="wp_dp_listing_off_days"]');
            if (_adding_date == date_field.val()) {
                var response = {
                    type: "success",
                    msg: wp_dp_listing_strings.off_day_already_added
                };
                alert(wp_dp_listing_strings.off_day_already_added);
                _add_date = false;
                counter = 0;
            }
        });

        if (typeof _day !== "undefined" && typeof _month !== "undefined" && typeof _year !== "undefined" && _add_date === true) {
            var thisObj = jQuery(".book-btn");
            wp_dp_show_loader(".book-btn", "", "button_loader", thisObj);
            adding_off_day = $.ajax({
                url: wp_dp_globals.ajax_url,
                method: "POST",
                data: {
                    off_day_day: _day,
                    off_day_month: _month,
                    off_day_year: _year,
                    listing_add_counter: _this_id,
                    action: "wp_dp_listing_off_day_to_list_backend"
                },
                dataType: "json"
            }).done(function (response) {
                if (typeof response.html !== "undefined") {
                    no_off_day_msg.remove();
                    _this_append.append(response.html);
                    this_act_msg.html(wp_dp_listing_strings.off_day_added);
                    jQuery(".calendar-holder").slideToggle("fast");
                }
                $("#wp-dp-dev-cal-holder-" + _this_id).slideUp("fast");
                counter = 0;
            }).fail(function () {
                counter = 0;
            });
        }
    }
});
/*
 * 
 */
function wp_dp_review_detail_content(review_id) {
    var ajax_url = wp_dp_backend_globals.ajax_url;
    var main_div = jQuery(".review-content-wrapper-" + review_id + "");
    main_div.html('<span class="loader-holder"><img src="' + wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
    var dataString = 'review_id=' + review_id + '&action=wp_dp_review_detail_content';
    jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: dataString,
        success: function (response) {
            main_div.html(response);
        }
    });
}
function wp_dp_promotions_detail_content(promotion_id) {
    
    var ajax_url = wp_dp_backend_globals.ajax_url;
    var main_div = jQuery(".promotions-content-wrapper-" + promotion_id + "");
    main_div.html('<span class="loader-holder"><img src="' + wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
    var dataString = 'promotion_id=' + promotion_id + '&action=wp_dp_promotions_detail_content';
    jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: dataString,
        success: function (response) {
            main_div.html(response);
        }
    });
}
function wp_dp_email_alerts_detail_content(email_alert_id) {
    
    var ajax_url = wp_dp_backend_globals.ajax_url;
    var main_div = jQuery(".email-alerts-content-wrapper-" + email_alert_id + "");
    main_div.html('<span class="loader-holder"><img src="' + wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
    var dataString = 'email_alert_id=' + email_alert_id + '&action=wp_dp_email_alerts_detail_content';
    jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: dataString,
        success: function (response) {
            main_div.html(response);
        }
    });
}



function wp_dp_transaction_detail_content(transaction_id) {
    
    var ajax_url = wp_dp_backend_globals.ajax_url,
		main_div = jQuery(".transaction-content-wrapper-" + transaction_id + ""),
		dataString = 'transaction_id=' + transaction_id + '&action=wp_dp_transaction_detail_content';
    
	main_div.html('<span class="loader-holder"><img src="' + wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
	
	jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: dataString,
        success: function (response) {
            main_div.html(response);
        }
    });
	
}
function wp_dp_review_status_change(action_value, trans_id){
	var ajax_url = wp_dp_backend_globals.ajax_url,
		trans_action = action_value,
		post_id =  trans_id,
		dataString = 'trans_action=' + trans_action + '&post_id=' + post_id + '&action=wp_dp_review_status_change';	
		
	jQuery('.wp_dp_status_action_'+post_id).html('<span class="loader-holder"><img src="' +wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
	jQuery.ajax({
		type: 'POST',
		url: ajax_url,
		data: dataString,
		dataType: "json",
		success: function(response){
			jQuery('.wp_dp_status_action_'+post_id).html(response.msg);
		}
	})		
}