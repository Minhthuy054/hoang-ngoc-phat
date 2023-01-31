(function ($) {
    $(function () {
        $(".email-listings-top").click(function () {
            $(".listing-alert-container-top .validation").addClass("hide");
            //$(".name-input-top").val('');
            $(".listing-alert-container-top").slideToggle();
            return false;
        });
       
        $(".btn-close-listing-alert-box").click(function () {
            $(".listing-alert-container-top").slideToggle();
            return false;
        });
        $('body').on('click', '.listingalert-submit', function () {
            
            var returnType = wp_dp_validation_process(jQuery(".listing-alert-box"));
            if (returnType == false) {
                return false;
            }
            
            var email = $(".email-input-top").val();
            // This one is removed
            var name = $(".name-input-top").val();
            //var name = email;
            var frequency = $('input[name="alert-frequency"]:checked').val();
            if (typeof frequency == "undefined") {
                frequency = "never";
            }
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var thisObj = jQuery('.listingalert-submit-button');
            wp_dp_show_loader('.listingalert-submit-button', '', 'button_loader', thisObj);
            $.ajax({
                "type": "POST",
                "url": wp_dp_notifications.ajax_url,
                "data": {
                    "action": "wp_dp_create_listing_alert",
                    "email": email,
                    "name": name,
                    "frequency": frequency,
                    "location": window.location.toString(),
                    "security": wp_dp_notifications.security,
                },
                "dataType": "json",
                "success": function (response) {

                    wp_dp_show_response(response, '', thisObj);
                    if (response.type == 'success') {
                        $(".name-input-top").val('');
                    }
                },
            });
            return false;
        });
    });
})(jQuery);

function wp_dp_dashboard_tab_load_listing_alerts(tabid, type, admin_url, uid, pkg_array, page_id_all, tab_options) {
    var dataString = "wp_dp_uid=" + uid + "&action=wp_dp_employer_listingalerts" + "&page_id_all=" + page_id_all;
    ajaxRequest = jQuery.ajax({
        type: "POST",
        url: admin_url,
        data: dataString,
        success: function (response) {
            alert("a");
            jQuery("#listing-alerts").html(response);
            wp_dp_change_dashboard_tab(tab_options);
        }
    });
}