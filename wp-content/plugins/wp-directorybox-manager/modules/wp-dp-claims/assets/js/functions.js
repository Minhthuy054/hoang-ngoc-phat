
jQuery(document).on("click", "#wp_dp_claim_listing_submit", function () {
    "use strict";
    var returnType = wp_dp_validation_process(jQuery("#wp_dp_claim_listing"));
    if (returnType == false) {
        return false;
    }
    var thisObj = jQuery(this);
    var this_loader_Obj = jQuery(".claim-request-holder");
    wp_dp_show_loader(".claim-request-holder", "", "button_loader", this_loader_Obj);

    thisObj.prop('disabled', true);
    var serilaized_data = jQuery('#wp_dp_claim_listing').serialize();
    var dataString = serilaized_data + '&action=claim_listing_from_save';
    var ajax_url = jQuery('#wp_dp_claim_ajax_url').val();

    jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            thisObj.prop('disabled', false);
            wp_dp_show_response(response, "", this_loader_Obj);
            if (response.type == 'success') {
                if (jQuery('#wp_dp_claim_user_login').val() != '1') {
                    jQuery('#wp_dp_claim_listing_user_name').val('');
                    jQuery('#wp_dp_claim_listing_user_email').val('');
                }
                jQuery('#wp_dp_claim_listing_reason').val('');
                jQuery('.claim_term_policy input[name=term_policy]').attr('checked', false);
            }
        }
    });
    return false;
});

jQuery(document).on("click", "#wp_dp_flag_listing_submit", function () {
    "use strict";
    var returnType = wp_dp_validation_process(jQuery("#wp_dp_flag_listing"));
    if (returnType == false) {
        return false;
    }
    var thisObj = jQuery(this);
    var this_loader_Obj = jQuery(".flag-request-holder");
    wp_dp_show_loader(".flag-request-holder", "", "button_loader", this_loader_Obj);

    thisObj.prop('disabled', true);
    var serilaized_data = jQuery('#wp_dp_flag_listing').serialize();
    var dataString = serilaized_data + '&action=flag_listing_from_save';
    var ajax_url = jQuery('#wp_dp_flag_ajax_url').val();

    jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            thisObj.prop('disabled', false);
            wp_dp_show_response(response, "", this_loader_Obj);
            if (response.type == 'success') {
                if (jQuery('#wp_dp_flag_user_login').val() != '1') {
                    jQuery('#wp_dp_flag_listing_user_name').val('');
                    jQuery('#wp_dp_flag_listing_user_email').val('');
                }
                jQuery('#wp_dp_flag_listing_reason').val('');
                jQuery('.flag_term_policy input[name=term_policy]').attr('checked', false);
            }
        }
    });
    return false;
});

function wp_dp_claim_action_change(action_value, claim_id){
	
	var ajax_url = wp_dp_backend_globals.ajax_url,
		claim_action = action_value,
		post_id =  claim_id,
		dataString = 'claim_action=' + claim_action + '&post_id=' + post_id + '&action=wp_dp_claim_update_action';	
		
	jQuery('.wp_dp_claim_action_'+post_id).html('<span class="loader-holder"><img src="' + wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
		
	jQuery.ajax({
		type: 'POST',
		url: ajax_url,
		data: dataString,
		dataType: "json",
		success: function(response){
			jQuery('.wp_dp_claim_action_'+post_id).html(response.msg);
		}
	})		
}

function wp_dp_promotion_ststus_change(value, id) {
	var ajax_url = ajax_url = wp_dp_backend_globals.ajax_url;
	jQuery('.wp_dp_promotion_action_' + id).html('<span class="loader-holder"><img src="' + wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
	var dataString = 'promotion_id=' + id + '&promotion_value=' + value + '&action=wp_dp_promotion_ststus_change';
	jQuery.ajax({
		type: "POST",
		url: ajax_url,
		data: dataString,
		dataType: 'json',
		success: function (response) {
			jQuery('.wp_dp_promotion_action_'+id).html(response.msg);
		}
	});
}

function wp_dp_transaction_status_change(action_value, trans_id){
	
	var ajax_url = wp_dp_backend_globals.ajax_url,
		trans_action = action_value,
		post_id =  trans_id,
		dataString = 'trans_action=' + trans_action + '&post_id=' + post_id + '&action=wp_dp_trans_action_change';	
		
	jQuery('.wp_dp_trans_action_'+post_id).html('<span class="loader-holder"><img src="' + wp_dp_backend_globals.plugin_url + 'assets/backend/images/ajax-loader.gif" alt=""></span>');
		
	jQuery.ajax({
		type: 'POST',
		url: ajax_url,
		data: dataString,
		dataType: "json",
		success: function(response){
			jQuery('.wp_dp_trans_action_'+post_id).html(response.msg);
		}
	})		
}