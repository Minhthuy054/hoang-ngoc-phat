var listingFilterAjax;
function wp_dp_listing_hide(thisObj, listing_id, member_id, listing_short_counter) {

    "use strict";
    var hide_icon_class = jQuery(thisObj).find("i").attr('class');
    var loader_class = 'fancy-spinner';
    jQuery(thisObj).find("i").removeClass(hide_icon_class).addClass(loader_class);
    var dataString = 'listing_id=' + listing_id + '&member_id=' + member_id + '&action=wp_dp_listing_hide_submit&listing_short_counter=' + listing_short_counter;
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: dataString,
        dataType: "json",
        success: function (response) {
            if (response.status == true) {
                jQuery(thisObj).closest('.listing-row').slideUp(700);
                if (jQuery('#hidden-listing-' + listing_short_counter).length) {        // use this if you are using id to check
                    jQuery('#hidden-listing-' + listing_short_counter).append(response.new_element);
                } else {
                    var hidden_string = '<div class="directorybox-hidden-listing"><div class="row"><div id="hidden-listing-' + listing_short_counter + '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div></div></div>';
                    jQuery('#directorybox-listing-' + listing_short_counter).append(hidden_string);
                    jQuery('#hidden-listing-' + listing_short_counter).append(response.new_element);
                }

            }
        }
    });
}
function wp_dp_listing_only_hide(thisObj, listing_id, member_id) {

    "use strict";
    var hide_icon_class = jQuery(thisObj).find("i").attr('class');
    var loader_class = 'fancy-spinner';
    jQuery(thisObj).find("i").removeClass(hide_icon_class).addClass(loader_class);
    var dataString = 'listing_id=' + listing_id + '&member_id=' + member_id + '&action=wp_dp_listing_hide_submit&response_type=short';
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: dataString,
        dataType: "json",
        success: function (response) {
            if (response.status == true) {
                jQuery(thisObj).html();
                jQuery(thisObj).replaceWith(response.new_element);

            }
        }
    });
}