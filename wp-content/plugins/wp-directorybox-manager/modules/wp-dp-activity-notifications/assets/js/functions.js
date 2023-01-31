jQuery(document).ready(function ($) {
    /*
     * Hide Notification from Member Dashboard
     */
    jQuery(document).on("click", ".hide_notification", function () {
        thisObj = jQuery(this);
        var id = thisObj.parent('li').data('id');
        wp_dp_show_loader('.loader-holder');
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: wp_dp_globals.ajax_url,
            data: 'id=' + id + '&action=wp_dp_hide_notification',
            success: function (response) {
                wp_dp_show_response(response);
                thisObj.parent('li').remove();
                if( jQuery('.activities-list-holder').length > 0 ){
                    var count = jQuery( '#activities-counts' ).text();
                    count -= 1;
                    jQuery( '#activities-counts' ).text( count );
                    jQuery( '.activities-count #heading-counts' ).text( count );
                    jQuery('.activities-list-holder .activities-list #activity-'+ id).remove();
                    if(  count == 0 ){
                        jQuery('.activities-list-holder').hide();
                    }
                }
            }
        });
    });
    
    jQuery(document).on("click", ".top_hide_notification", function () {
        thisObj = jQuery(this);
        var id = thisObj.closest('li').data('id');
        jQuery(thisObj).find('i').removeClass('icon-close');
        jQuery(thisObj).find('i').addClass('fancy-spinner');
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: wp_dp_globals.ajax_url,
            data: 'id=' + id + '&action=wp_dp_hide_notification',
            success: function (response) {
                jQuery(thisObj).find('i').removeClass('fancy-spinner');
                jQuery(thisObj).find('i').addClass('icon-close');
                thisObj.closest('li').remove();
                
                if( jQuery('.activities-list-holder').length > 0 ){
                    var count = jQuery( '#activities-counts' ).text();
                    count -= 1;
                    jQuery( '#activities-counts' ).text( count );
                    jQuery( '.activities-count #heading-counts' ).text( count );
                    if(  count == 0 ){
                        jQuery('.activities-list-holder').hide();
                    }
                }
                
                if( jQuery('.user-holder .user-notification #activity-'+ id).length > 0 ){
                    jQuery('.user-holder .user-notification #activity-'+ id).remove();
                }
                wp_dp_show_response(response);
            }
        });
    });

    /*
     * Clearing All Notifications from member dashboard
     */
    jQuery(document).on("click", ".wp-dp-clear-notifications a", function () {
        thisObj = jQuery(this);
        wp_dp_show_loader('.loader-holder');
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: wp_dp_globals.ajax_url,
            data: 'action=wp_dp_clear_all_notification',
            success: function (response) {
                wp_dp_show_response(response);
                thisObj.closest('.user-notification').remove();
            }
        });
    });
    
    
    
    /*
     * Load More for notifications on dashboard
     */
    jQuery(document).on("click", ".load-more-notifications", function () {
       var thisObj = jQuery(this);
       wp_dp_show_loader(".load-more-notifications", "", "button_loader", thisObj);
       var current_page    = jQuery("#current_page").val();
       var max_num_pages   = jQuery("#max_num_pages").val();
       current_page    = parseInt(current_page) + 1;
       jQuery.ajax({
            type: 'POST',
            url: wp_dp_globals.ajax_url,
            data: 'current_page='+current_page+'&action=wp_dp_notification_loadmore',
            success: function (response) {
                jQuery(".user-notification ul").append(response);
                jQuery("#current_page").val(current_page);
                if( max_num_pages == current_page ){
                    jQuery(".load-more-notifications").hide();
                }
                wp_dp_hide_button_loader(thisObj);
            }
        });
    });
    
});