/*
 * 
 * Directory Box Member Added Favourite function
 */
function wp_dp_member_listing_favourite(thisObj, listing_id, member_id, favourite, favourited, before_icon, after_icon, strings) {

    "use strict";
	
    var favourite_icon_class = jQuery(thisObj).find("i").attr('class');

    var loader_class = 'fancy-spinner';
    jQuery(thisObj).find("i").removeClass(favourite_icon_class).addClass(loader_class);
    var dataString = 'listing_id=' + listing_id + '&member_id=' + member_id + '&action=wp_dp_favourite_submit';
	var scrollTop	=	jQuery(window).scrollTop();
	
	//return false;
    jQuery.ajax({
        type: "POST",
        url: wp_dp_favourites.admin_url,
        data: dataString,
        dataType: "json",
        success: function (response) {
			
            if (response.status == true) {
                jQuery(thisObj).removeClass('favourite').addClass('favourite');
                jQuery(thisObj).find("i").removeClass(loader_class).addClass(after_icon);
                jQuery(thisObj).find(".option-content span").html(favourited);
                var msg_obj = {msg: strings.added, type: 'success'};

                wp_dp_show_response(msg_obj);
                if (response.listing_count !== 'undefined' && response.listing_count !== '') {
                    jQuery(thisObj).find(".likes-count span").text(response.listing_count);
                }
            } else {
                if (response.current_user == true) {
                     jQuery(thisObj).find("i").removeClass(loader_class).addClass(before_icon);
                    var msg_obj = {msg: response.msg, type: 'success'};
                    wp_dp_show_response(msg_obj);
                } else {
                    jQuery(thisObj).removeClass('favourite').addClass('favourite');
                    jQuery(thisObj).find("i").removeClass(loader_class).addClass(before_icon);
                    jQuery(thisObj).find(".option-content span").html(favourite);
                    var msg_obj = {msg: strings.removed, type: 'success'};
                    wp_dp_show_response(msg_obj);
                    if (response.listing_count !== 'undefined' && response.listing_count !== '') {
                        jQuery(thisObj).find(".likes-count span").text(response.listing_count);
                    }
                }
            }
			
        }
    });	
	jQuery(document).ajaxComplete(function(){
		jQuery('body').scrollTop(scrollTop);
	});
}


function wp_dp_member_favourite(thisObj, listing_id, member_id, favourite, favourited, before_icon, after_icon, strings) {


    "use strict";
    var favourite_icon_class = jQuery(thisObj).find("i").attr('class');

    var loader_class = 'fancy-spinner';
    jQuery(thisObj).find("i").removeClass(favourite_icon_class).addClass(loader_class);
    var dataString = 'listing_id=' + listing_id + '&member_id=' + member_id + '&action=wp_dp_favourite_submit';
    jQuery.ajax({
        type: "POST",
        url: wp_dp_favourites.admin_url,
        data: dataString,
        dataType: "json",
        success: function (response) {
            if (response.status == true) {
                jQuery(thisObj).removeClass('favourite').addClass('favourite');
                jQuery(thisObj).html(after_icon + favourited);
                var msg_obj = {msg: strings.added, type: 'success'};

                wp_dp_show_response(msg_obj);
                if (response.listing_count !== 'undefined' && response.listing_count !== '') {
                    jQuery(thisObj).parent().find(".likes-count span").text(response.listing_count);
                }
            } else {
                if (response.current_user == true) {
                    jQuery(thisObj).html(before_icon + favourite);
                    var msg_obj = {msg: response.msg, type: 'success'};
                    wp_dp_show_response(msg_obj);
                } else {
                    jQuery(thisObj).removeClass('favourite').addClass('favourite');
                    jQuery(thisObj).html(before_icon + favourite);
                    var msg_obj = {msg: strings.removed, type: 'success'};
                    wp_dp_show_response(msg_obj);
                    if (response.listing_count !== 'undefined' && response.listing_count !== '') {
                        jQuery(thisObj).parent().find(".likes-count span").text(response.listing_count);
                    }
                }
            }
        }
    });
}

/*
 * 
 * Directory Box Member Removed Favourite function
 */
jQuery(document).on("click", ".delete-favourite", function () {
    var thisObj = jQuery(this);
    var listing_id = thisObj.data('id');
    var delete_icon_class = thisObj.find("i").attr('class');
    var loader_class = 'fancy-spinner';
    var dataString = 'listing_id=' + listing_id + '&action=wp_dp_removed_favourite';
    jQuery('#id_confrmdiv').show();
    jQuery('#id_truebtn').click(function () {
        thisObj.find('span').remove();
        thisObj.find('i').removeClass(delete_icon_class);
        thisObj.find('i').addClass(loader_class);

        jQuery.ajax({
            type: "POST",
            url: wp_dp_favourites.admin_url,
            data: dataString,
            dataType: "json",
            success: function (response) {
                thisObj.find('i').removeClass(loader_class).addClass(delete_icon_class);
                if (response.status == true) {

                    thisObj.closest('li').hide('slow', function () {
                        thisObj.closest('li').remove();
                    });

                    var msg_obj = {msg: response.message, type: 'success'};
                    wp_dp_show_response(msg_obj);
                    if (response.listing_count !== 'undefined' && response.listing_count !== '') {
                        jQuery('.like-btn').find(".likes-count span").text(response.listing_count);
                    }
                }
            }
        });

        jQuery('#id_confrmdiv').hide();
        return false;
    });
    jQuery('#id_falsebtn').click(function () {
        jQuery('#id_confrmdiv').hide();
        return false;
    });
    return false;
});