/*
 * Login Block
 */


/*** User Login Authentication*/

function wp_dp_user_authentication(admin_url, id, thisObjClass) {
    "use strict";
    
    var returnType = wp_dp_validation_process(jQuery("#ControlForm_"+id+""));
    if (returnType == false) {
        return false;
    }
    
    //var formDivClass = ".login-form-id-" + id;
    var formDivClass = "";
    
    if (typeof thisObjClass == "undefined" || thisObjClass == "") {
        var thisObjClass = ".ajax-login-button";
    } else if (thisObjClass === ".shortcode-ajax-login-button") {
        formDivClass = "";
    }
    var thisObj = jQuery(thisObjClass);
    wp_dp_show_loader(thisObjClass, "", "button_loader", thisObj);
    
    function newValues(id) {
        var serializedValues = jQuery("#ControlForm_" + id).serialize();
        return serializedValues;
    }
    
    var lang_value = jQuery("#ControlForm_" + id).find('input[name="lang"]').val();
    var lang_html = '';
    if(typeof lang_value !== 'undefined'){
     lang_html = '<input name="lang" value="'+lang_value+'">';   
    }
    
    var serializedReturn = newValues(id);
    jQuery(".login-form-id-" + id + " .status-message").removeClass("success error");
    jQuery.ajax({
        type: "POST",
        url: admin_url,
        dataType: "json",
        data: serializedReturn,
        success: function(data) {
            if (data.type == "error") {
                wp_dp_show_response(data, formDivClass, thisObj);
            } else if (data.type == "success") {
                wp_dp_show_response(data, formDivClass, thisObj);
                //document.location.href = data.redirecturl;
                
                console.log(data.redirecturl);
                
                $(thisObjClass).append('\
                <form style="display:none;" id="hiden-form-id-' + id + '" action="' + data.redirecturl + '">\
                <input type="submit" value="submit">\
                '+lang_html+'\
                </form>');
                $('#hiden-form-id-' + id).submit();
            }
        }
    });
}

/*
 * Company Name based on Profile Type
 */

jQuery(document).on("change", ".wp_dp_profile_type", function() {
    "use strict";
    var current_val = jQuery(this).val();
    if (current_val == "company") {
        jQuery(".wp-dp-company-name").show();
        jQuery(".display-name-field").hide();
        jQuery(".member-type-field").show();
        jQuery(".company-valid").addClass('wp-dp-dev-req-field');
        
        
    } else {
        jQuery(".wp-dp-company-name").hide();
        jQuery(".display-name-field").show();
        jQuery(".member-type-field").hide();
        jQuery(".company-valid").removeClass('wp-dp-dev-req-field');
        
    }
});

jQuery(document).on("click", ".user-tab-register, .user-tab-login", function() {

    "use strict";

    var thisObj = jQuery(this);

    if (thisObj.hasClass("user-tab-register")) {
        jQuery(".tab-content").find("div[id^='user-login-tab-']").removeClass('active');
        jQuery(".tab-content").find("div[id^='user-login-tab-']").removeClass('in');
        var tab_name = "register";
        jQuery('.cs-demo-login').removeClass('wp-dp-focused');
    } else {
        jQuery(".tab-content").find("div[id^='user-register-']").removeClass('active');
        jQuery(".tab-content").find("div[id^='user-register-']").removeClass('in');
        var tab_name = "login";

    }

    var serializedValues = "member_user_tab=" + tab_name;

    jQuery.ajax({

        type: "POST",

        url: wp_dp_globals.ajax_url,

        data: serializedValues + "&action=wp_dp_set_user_tab_cookie",

        success: function(response) {}

    });
    
});

function wp_dp_registration_validation(admin_url, id, thisObjClas) {
    

    "use strict";
     var returnType = wp_dp_validation_process(jQuery("#wp_signup_form_"+id+""));
    if (returnType == false) {
        return false;
    }
    $(".status-message").removeClass("text-danger").hide();

    //var formDivID = "#user-register-" + id + " .modal-body";
    var formDivID = "";
    

    if (typeof thisObjClas == "undefined" || thisObjClas == "") {

        thisObjClas = ".ajax-signup-button";

    } else if (thisObjClas === ".shortcode-ajax-signup-button") {

        formDivID = "";

    }

    var thisObj = jQuery(thisObjClas);

    wp_dp_show_loader(thisObjClas, "", "button_loader", thisObj);



    function newValues(id) {

        jQuery("#user_profile").val();

        var serializedValues = jQuery("#wp_signup_form_" + id).serialize() + "&id=" + id;

        return serializedValues;

    }

    var serializedReturn = newValues(id);

    jQuery("div#result_" + id).removeClass("success error");

    jQuery.ajax({

        type: "POST",

        url: admin_url,

        dataType: "json",

        data: serializedReturn,

        success: function(response) {
            if( response.demo_user_error === 'true' ){
                jQuery('#user-register-'+ id +' .user-tab-login').trigger('click');
                jQuery('.login-form-id-'+ id +' .cs-demo-login').addClass('wp-dp-focused');
                jQuery( '.login-form-id-'+ id +' .status-message' ).addClass('error');
                jQuery( '.login-form-id-'+ id +' .status-message' ).show();
                jQuery( '.login-form-id-'+ id +' .status-message' ).html('<p>'+ response.msg +'</p>');
                wp_dp_show_response('', formDivID, thisObj);
                setTimeout(function() {
                    jQuery( '.login-form-id-'+ id +' .status-message' ).hide();
                    jQuery( '.login-form-id-'+ id +' .status-message' ).removeClass('error');
                    jQuery( '.login-form-id-'+ id +' .status-message' ).html('');
                }, 5000);
            }else{
                wp_dp_show_response(response, formDivID, thisObj);
            }

        }

    });

}

function wp_dp_blink_div(id){
    jQuery('.demo-login-agency-'+ id +'.wp-dp-focused').fadeOut(1500, function(){
        jQuery('.demo-login-agency-'+ id +'.wp-dp-focused').fadeIn(600, function(){
            wp_dp_blink_div('.demo-login-agency-'+ id +'.wp-dp-focused');
        });
    });
}

$(".login-form .nav-tabs > li").on("click", function(e) {
    "use strict";
    if (!$(this).hasClass("active")) {
        jQuery(this).closest(".modal-body .loader").show();
        if ($(".login-form .modal-body .loader").length == 0) {

            $(".login-form .modal-body").append('<span class="loader"></span>');

        }
        setTimeout(function() {
            $(".login-form .modal-body .loader").fadeOut();
        }, 400);
    }
});

$(".login-form .nav-tabs > li").on("click", function(e) {
    "use strict";
        $(this).removeClass("active")
});
jQuery(document).ready(function($) {
    "use strict";

    jQuery(".login-box").hide();
    
    jQuery(".login-link").on("click", function(e) {
        e.preventDefault();
        jQuery(".nav-tabs, .nav-tabs~.tab-content, .forgot-box").fadeOut(function() {
            jQuery(".login-box").fadeIn();
        });
    });

    /*
     * frontend login tabs function
     */

    jQuery(".login-link-page").on("click", function(e) {
        e.preventDefault();
        jQuery(".nav-tabs-page, .nav-tabs-page~.tab-content-page, .forgot-box").fadeOut(function() {
            jQuery(".login-box").fadeIn();
            jQuery(".tab-content-page").fadeOut();
        });
    });

    /*
     * frontend register tabs function
     */

    jQuery(".register-link").on("click", function(e) {
        e.preventDefault();
        jQuery(".login-box, .forgot-box").fadeOut(function() {
            jQuery(".nav-tabs, .nav-tabs~.tab-content").fadeIn();
        });
    });

    /*
     * frontend register tabs function
     */

    jQuery(".register-link-page").on("click", function(e) {
        e.preventDefault();
        jQuery(".login-box, .forgot-box").fadeOut(function() {
            jQuery(".tab-content-page").fadeIn();
            jQuery(".nav-tabs-page").fadeIn();
        });
    });

    /*
     * frontend page element forgotpassword function
     */

    jQuery(".user-forgot-password-page").on("click", function(e) {
        e.preventDefault();
        jQuery(".login-box, .nav-tabs-page, .nav-tabs-page~.tab-content-page").fadeOut(function() {
            jQuery(".forgot-box").fadeIn();
        });
    });

    /*
    * frontend forgotpassword function
     */

    jQuery(".user-forgot-password").on("click", function(e) {
        e.preventDefault();
        jQuery(".login-box, .nav-tabs, .nav-tabs~.tab-content").fadeOut(function() {
            jQuery(".forgot-box").fadeIn();
        });
    });
});