function validate(evt) {
    "use strict";
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}

function checkName(el, id) {
    "use strict";
    var ar_ext = ['pdf', 'doc', 'rtf', 'docx'];
// - coursesweb.net
    // get the file name and split it to separe the extension
    var name = el.value;
    var ar_name = name.split('.');
    // for IE - separe dir paths (\) from name
    var ar_nm = ar_name[0].split('\\');
    for (var i = 0; i < ar_nm.length; i++)
        var nm = ar_nm[i];
    // check the file extension
    var re = 0;
    for (var i = 0; i < ar_ext.length; i++) {
        if (ar_ext[i] == ar_name[1]) {
            re = 1;
            break;
        }
    }
    if (re != 1) {
        jQuery('.status-msg-' + id + '').addClass("error-msg");
        el.value = '';
    } else {
        jQuery('.status-msg-' + id + '').removeClass("error-msg");
        // add the name in 'to'
        var html_txt = "<div id='user-selected-file-" + id + "'><div class='alert alert-dismissible user-resume' id='user-file-" + id + "'><div>" + nm + "<div class='gal-edit-opts close'><a href=\"javascript:wp_dp_del_media('user-file-" + id + "')\" class='delete'><span aria-hidden='true'><i class=\"icon-close2\"></i></span></a></div></div></div></div>";
        jQuery("#user-selected-file-" + id + "").html(html_txt);

    }
}

function wp_dp_del_media(id) {
    "use strict";
    jQuery('#' + id + '').hide();
    jQuery('#' + id).val('');
    jQuery('#' + id).next().show();
}

function wp_dp_enquiry_detail(enquiry_id, type, read_status) {
    "use strict";
    wp_dp_show_loader('.loader-holder');
    jQuery(this).addClass('active');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_enquiry_detail&enquiry_id=' + enquiry_id + '&type=' + type,
        success: function (response) {
            if(jQuery( '#enuiry-'+ enquiry_id ).hasClass('unread')){
                var all_count = jQuery('.dashboard-nav').find('.accordian').find('b.count-all-enquiries').html();
                if( typeof all_count !== "undefined" && all_count > 0 ){
                    all_count -= 1;
                    jQuery('.dashboard-nav').find('.accordian').find('b.count-all-enquiries').html(all_count);
                }
                if( type == 'my'){
                    var count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-enquiries').html();
                    if( typeof count !== "undefined" && count > 0 ){
                        count -= 1;
                        jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-enquiries').html(count);
                    }
                }else if( type == 'received'){
                    var received_count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-enquiries').html();
                    if( typeof received_count !== "undefined" && received_count > 0 ){
                        received_count -= 1;
                        jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-enquiries').html(received_count);
                    }
                }
            }
            wp_dp_hide_loader('.loader-holder');
            jQuery('.user-holder').html(response);
        }
    });
}

function wp_dp_arrange_viewing_detail(viewing_id, type, read_status) {

    "use strict";
    wp_dp_show_loader('.loader-holder');
    jQuery(this).addClass('active');
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_arrange_viewing_detail&viewing_id=' + viewing_id + '&type=' + type,
        success: function (response) {
            
            if(jQuery( '#viewing-'+ viewing_id ).hasClass('unread')){
                var all_count = jQuery('.dashboard-nav').find('.accordian').find('b.count-all-viewings').html();
                if( typeof all_count !== "undefined" && all_count > 0 ){
                    all_count -= 1;
                    jQuery('.dashboard-nav').find('.accordian').find('b.count-all-viewings').html(all_count);
                }
                if( type == 'my'){
                    var count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-viewings').html();
                    if( typeof count !== "undefined" && count > 0 ){
                        count -= 1;
                        jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-viewings').html(count);
                    }
                }else if( type == 'received'){
                    var received_count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-viewings').html();
                    if( typeof received_count !== "undefined" && received_count > 0 ){
                        received_count -= 1;
                        jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-viewings').html(received_count);
                    }
                }
            }
            wp_dp_hide_loader('.loader-holder');
            jQuery('.user-holder').html(response);
        }
    });
}

function wp_dp_discussion_submit(admin_url, file_field) {
    "use strict";
     var thisObj = jQuery(".discussion-submit");
    wp_dp_show_loader(".discussion-submit", "", "button_loader", thisObj);
    
    var serializedValues = jQuery("#discussion-form").serialize();
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        dataType: 'json',
        data: serializedValues + '&action=wp_dp_discussion_submit',
        success: function (response) {
            wp_dp_show_response(response);
            if (response.empty == true) {
                jQuery('#comment_message').css('border', 'solid 1px red');
            } else if (response.empty == false) {
                jQuery('#comment_message').css('border', '');
            }
            if (response.type == 'success') {
                if (response.comments_count > 1) {
                    jQuery(".element-title h3").html(response.comments_number);
                    jQuery("#discussion-list").append(response.new_comment);
                } else {
                    jQuery(".order-discussions-holder").html('<div class="order-discussions"><div class="element-title"><h3>' + response.comments_number + '</h3></div><ul id="discussion-list" class="order-discussion-list">' + response.new_comment + '</ul></div>');
                }
                jQuery('#comment_message').val('');
            }
        }
    });
}

function wp_dp_update_enquiry_status(sel, enquiry_id, admin_url) {
    "use strict";
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        dataType: 'json',
        data: 'action=wp_dp_update_enquiry_status&enquiry_id=' + enquiry_id + '&enquiry_status=' + sel.value,
        success: function (response) {
            wp_dp_show_response(response);
        }
    });
}

jQuery(document).on('click', '#unread-read-enquiry', function () {
    
    $( "#enquiry_read_status" ).trigger( "click" );
    
});

jQuery(document).on('click', '#enquiry_read_status', function () {
    "use strict";
    if (jQuery(this).is(":checked")) {
        jQuery(this).val('1');
    } else {
        jQuery(this).val('0');
    }
    var enquiry_read_status = jQuery(this).val();
    var enquiry_id = jQuery('#enquiry_id').val();
    var user_status = jQuery('#user_status').val();
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_update_enquiry_read_status&enquiry_id=' + enquiry_id + '&enquiry_read_status=' + enquiry_read_status + '&user_status=' + user_status,
        dataType: 'json',
        success: function (response) {
            wp_dp_show_response(response);
            
            var all_count = jQuery('.dashboard-nav').find('.accordian').find('b.count-all-enquiries').html();
            var submitted_count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-enquiries').html();
            var received_count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-enquiries').html();
            if (response.read_type == 'read') {
                if (typeof all_count !== "undefined"){
                    var sum = 1;
                    var new_val = parseInt(all_count) + parseInt(sum);
                    jQuery('.dashboard-nav').find('.accordian').find('b.count-all-enquiries').html(new_val);
                }
                if (typeof submitted_count !== "undefined" && user_status === 'buyer'){
                    var sum = 1;
                    var new_val = parseInt(submitted_count) + parseInt(sum);
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-enquiries').html(new_val);
                }
                if (typeof received_count !== "undefined" && user_status === 'seller'){
                    var sum = 1;
                    var new_val = parseInt(received_count) + parseInt(sum);
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-enquiries').html(new_val);
                }
                var read_unread = jQuery('.enquiry-read-checkbox').data('read');
            } else {
                if (typeof all_count !== "undefined"){
                    all_count -= 1;
                    jQuery('.dashboard-nav').find('.accordian').find('b.count-all-enquiries').html(all_count);
                }
                if (typeof submitted_count !== "undefined" && user_status === 'buyer'){
                    submitted_count -= 1;
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-enquiries').html(submitted_count);
                }
                if (typeof received_count !== "undefined" && user_status === 'seller' ){
                    received_count -= 1;
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-enquiries').html(received_count);
                }
                var read_unread = jQuery('.enquiry-read-checkbox').data('unread');
            }
            
            
//            var count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-enquiries').html();
//            if (response.read_type == 'read') {
//                if (typeof count !== "undefined"){
//                    var sum = 1;
//                    var new_val = parseInt(count) + parseInt(sum);
//                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-enquiries').html(new_val);
//                }
//                var read_unread = jQuery('.enquiry-read-checkbox').data('read');
//            } else {
//                if (typeof count !== "undefined"){
//                    count -= 1;
//                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-enquiries').html(count);
//                }
//                var read_unread = jQuery('.enquiry-read-checkbox').data('unread');
//            }
            //jQuery('.enquiry-read-checkbox label').attr('data-original-title', read_unread);
            jQuery('.enquiry-read-checkbox span').text(read_unread);
            


        }
    });
});

function wp_dp_update_viewing_status(sel, viewing_id, admin_url) {
    "use strict";
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        dataType: 'json',
        data: 'action=wp_dp_update_viewing_status&viewing_id=' + viewing_id + '&viewing_status=' + sel.value,
        success: function (response) {
            wp_dp_show_response(response);
        }
    });
}

jQuery(document).on('click', '#viewing_read_status', function () {
    "use strict";
    if (jQuery(this).is(":checked")) {
        jQuery(this).val('1');
    } else {
        jQuery(this).val('0');
    }
    var viewing_read_status = jQuery(this).val();
    var viewing_id = jQuery('#viewing_id').val();
    var user_status = jQuery('#viewing_user_status').val();
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_update_viewing_read_status&viewing_id=' + viewing_id + '&viewing_read_status=' + viewing_read_status + '&user_status=' + user_status,
        dataType: 'json',
        success: function (response) {
            wp_dp_show_response(response);
            var all_count = jQuery('.dashboard-nav').find('.accordian').find('b.count-all-viewings').html();
            var submitted_count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-viewings').html();
            var received_count = jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-viewings').html();
            if (response.read_type == 'read') {
                if (typeof all_count !== "undefined"){
                    var sum = 1;
                    var new_val = parseInt(all_count) + parseInt(sum);
                    jQuery('.dashboard-nav').find('.accordian').find('b.count-all-viewings').html(new_val);
                }
                if (typeof submitted_count !== "undefined" && user_status === 'buyer'){
                    var sum = 1;
                    var new_val = parseInt(submitted_count) + parseInt(sum);
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-viewings').html(new_val);
                }
                if (typeof received_count !== "undefined" && user_status === 'seller'){
                    var sum = 1;
                    var new_val = parseInt(received_count) + parseInt(sum);
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-viewings').html(new_val);
                }
                var read_unread = jQuery('.viewing-read-checkbox').data('read');
            } else {
                if (typeof all_count !== "undefined"){
                    all_count -= 1;
                    jQuery('.dashboard-nav').find('.accordian').find('b.count-all-viewings').html(all_count);
                }
                if (typeof submitted_count !== "undefined" && user_status === 'buyer'){
                    submitted_count -= 1;
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-submitted-viewings').html(submitted_count);
                }
                if (typeof received_count !== "undefined" && user_status === 'seller' ){
                    received_count -= 1;
                    jQuery('.dashboard-nav').find('.user_dashboard_ajax').find('b.count-received-viewings').html(received_count);
                }
                var read_unread = jQuery('.viewing-read-checkbox').data('unread');
            }
            jQuery('.viewing-read-checkbox label').attr('viewing-original-title', read_unread);
        }
    });
});

function wp_dp_closed_enquiry(enquiry_id) {
    "use strict";
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_closed_enquiry&enquiry_id=' + enquiry_id,
        dataType: 'json',
        success: function (response) {
            jQuery('.enquiry-status p').html('');
            jQuery('.enquiry-status p').html(response.msg);
            wp_dp_show_response(response);
        }
    });
}

function wp_dp_closed_arrange_viewing(viewing_id) {
    "use strict";
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: 'action=wp_dp_closed_viewing&viewing_id=' + viewing_id,
        dataType: 'json',
        success: function (response) {
            jQuery('.viewing-status p').html('');
            jQuery('.viewing-status p').html(response.msg);
            wp_dp_show_response(response);
        }
    });
}