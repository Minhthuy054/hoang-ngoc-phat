var $ = jQuery;

(function ($) {
    "use strict";
    $.fn.wp_dp_req_loop = function (callback, thisArg) {
        var me = this;
        return this.each(function (index, element) {
            return callback.call(thisArg || element, element, index, me);
        });
    };
})(jQuery);

var first_time = true;
function change_tab(tab_id, e) {
    "use strict";
    if (typeof e !== "undefined") {
        e.stopPropagation();
    }

    var change_tab_head = true;
    var this_li = $('.listing-settings-nav > li a[data-act="' + tab_id + '"]');

    // Used by Register and Add listing shortcode.
    if (this_li.hasClass('cond-listing-settings1')) {   
        var change_tab = true;
        var tab_container = this_li.data('act');
        var active_tab_container = $('.listing-settings-nav > li.active a').data('act');
        if (active_tab_container == 'listing-information') {
            if (!first_time) {
                change_tab = validate_register_add_listing_form($(this).parents('form'));
            } else {
                change_tab = true;
            }
        } else if (active_tab_container == 'listing-information') {
            change_tab = ($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0);
        }

        if ((active_tab_container == tab_container) || change_tab) {
            $("#listing-sets-holder ul.register-add-listing-tab-container").hide();
            $("." + tab_container + "-tab-container", $("#listing-sets-holder")).show();
        } else {
            change_tab_head = false;
        }
    }

    if (change_tab_head) {
        if ($(this_li).hasClass('cond-listing-settings1')) {
            $('html,body').animate({scrollTop: 0}, 1000);
        }

        if (!first_time || this_li.parent('li').hasClass('cond-listing-settings')) {
            $('.listing-settings-nav > li').removeClass('processing');
            if (tab_id == "listing-information") {
            } else if (tab_id == "listing-detail-info") {
            } else if (tab_id == "package") {
            } else if (tab_id == "payment-information") {
            } else if (tab_id == "activation") {
            }

            this_li.parent('li').addClass('active processing');
        }
    }

    if (first_time) {
        first_time = false;
    }
}

function wp_dp_user_avail(field) {

    var $ = jQuery;
    var ajaxurl = wp_dp_listing_strings.ajax_url;
    var email_pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    var user_email = $('input[name="wp_dp_user_email"]').val();
    var username = $('input[name="wp_dp_user"]').val();
    
    if (field == 'email' && user_email == '') {
        return false;
    }
    
    if (field == 'username' && username == '') {
        return false;
    }
    
    if (field == 'email' && ! email_pattern.test(user_email)) {

        var response = {
            type: 'error',
            msg: 'Please enter the correct email address.'
        };
        wp_dp_show_response(response);
        return false;
    }
    
    if (field == 'email') {
        $('#wp_dp_user_email_validation').html('');
        $('#wp-dp-email-field-holder').find('.checking-loader').html('<i class="fancy-spinner"></i>');
        $('#wp-dp-email-field-holder').find('.checking-loader').addClass('processing');
    } else if (field == 'username') {
        $('#wp_dp_user_name_validation').html('');
        $('#wp-dp-username-field-holder').find('.checking-loader').html('<i class="fancy-spinner"></i>');
        $('#wp-dp-username-field-holder').find('.checking-loader').addClass('processing');
    }

    var dataString = 'user_email=' + user_email
            + '&user_username=' + username
            + '&field_type=' + field
            + '&action=wp_dp_check_user_avail';
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: dataString,
        dataType: "json",
        success: function (response) {
            if (response.type == 'error') {
                $('.listing-detail-info-tab-container').addClass('listing-prevent');
                if (field == 'email') {
                    $("#wp_dp_user_email").addClass("usererror");
                    $("#wp_dp_user_email").parents('.field-holder').addClass("has-error");
                    $("#wp_dp_user_email").parents('.field-holder').removeClass("user-avail");
                    $('#wp_dp_user_email_validation').html('<i class="icon-cross"></i> ' + response.msg);
                } else if (field == 'username') {
                    $("#wp_dp_user").addClass("usererror");
                    $("#wp_dp_user").parents('.field-holder').addClass("has-error");
                    $("#wp_dp_user").parents('.field-holder').removeClass("has-error");
                    $('#wp_dp_user_name_validation').html('<i class="icon-cross"></i> ' + response.msg);
                }
            } else if (response.type == 'success') {
                $('.listing-detail-info-tab-container').removeClass('listing-prevent');
                if (field == 'email') {
                    $("#wp_dp_user_email").removeClass("usererror");
                    $("#wp_dp_user_email").parents('.field-holder').removeClass("has-error");
                    $("#wp_dp_user_email").parents('.field-holder').addClass("user-avail");
                    $('#wp_dp_user_email_validation').html('<i class="icon-checkmark"></i> ' + response.msg);
                } else if (field == 'username') {
                    $("#wp_dp_user").removeClass("usererror");
                    $("#wp_dp_user").parents('.field-holder').removeClass("has-error");
                    $("#wp_dp_user").parents('.field-holder').addClass("user-avail");
                    $('#wp_dp_user_name_validation').html('<i class="icon-checkmark"></i> ' + response.msg);
                }
            }
            if (field == 'email') {
                $('#wp-dp-email-field-holder').find('.checking-loader').html('');
                $('#wp-dp-email-field-holder').find('.checking-loader').removeClass('processing');
            } else if (field == 'username') {
                $('#wp-dp-username-field-holder').find('.checking-loader').html('');
                $('#wp-dp-username-field-holder').find('.checking-loader').removeClass('processing');
            }
            
            if ($("#wp_dp_user").parents('.field-holder').hasClass("has-error") || $("#wp_dp_user_email").parents('.field-holder').hasClass("has-error")) {
                $('.listing-detail-info-tab-container').addClass('listing-prevent');
            } else {
                $('.listing-detail-info-tab-container').removeClass('listing-prevent');
            }
        }
    });

    return false;
}

$(document).ready(function ($, e) {
    "use strict";
    if ($(".selectpicker").length != '') {
        $('.selectpicker').selectpicker({
            size: 5
        });
    }



    if ($(".service-list").length != '') {
        var timesRun = 0;
        setInterval(function () {
            timesRun++;
            if (timesRun === 1) {
                $('.service-list > ul').sortable({
                    handle: '.drag-option',
                    cursor: 'move'
                });
            }
        }, 500);
    }

    if ($(".floor-plan-list").length != '') {
        var timesRun = 0;
        setInterval(function () {
            timesRun++;
            if (timesRun === 1) {
                $('.floor-plan-list > ul').sortable({
                    handle: '.drag-option',
                    cursor: 'move'
                });
            }
        }, 500);
    }

});

$(document).on('submit', 'form.wp-dp-dev-payment-form', function () {
    "use strict";
    var returnType = wp_dp_validation_process(jQuery(".wp-dp-dev-payment-form"));
    if (returnType == false) {
        return false;
    }
    return validate_register_add_listing_form(this);
});

$(document).on('submit', 'form.wp-dp-dev-listing-form', function () {
    "use strict";
    var returnType = wp_dp_validation_process(jQuery(".wp-dp-dev-listing-form"));

    if (returnType == false) {
        return false;
    } else {
        var thisObj = jQuery('#update-listing-holder');
        wp_dp_show_loader('#update-listing-holder', '', 'button_loader', thisObj);
    }
    return validate_register_add_listing_form(this);
});

function validate_register_add_listing_form(that) {
    "use strict";
    var req_class = 'wp-dp-dev-req-field',
            _this_form = $(that),
            _this_id = $(that).data('id'),
            form_validity = 'valid';
    var is_already_animated = false;
    var animate_to = '';
    _this_form.find('.' + req_class).wp_dp_req_loop(function (element, index, set) {
        if ($(element).attr('id') == 'terms-' + _this_id) {
            if ($(element).is(':checked')) {
                $(element).next('label').css({"color": "#484848"});
            } else {
                $(element).next('label').css({"color": "#ff0000"});
                form_validity = 'invalid';
            }
        } else {
            if ($(element).is('select')) {
                if ($("option:selected", $(element)).attr('value') != '') {
                    $(element).next('.chosen-container').css({"border": "1px solid #eceef4"});
                } else if ($("option:selected", $(element)).attr('value') == '') {
                    form_validity = 'invalid';
                    $(element).next('.chosen-container').css({"border": "1px solid #ff0000"});
                    animate_to = $(element).parent().parent();
                }
            } else if ($(element).is('textarea')) {
                if ($(element).val() != '') {
                    $(element).parents('.jqte').attr("style", "border: 1px solid #eceef4 !important;");
                    $(element).attr("style", "border: 1px solid #eceef4 !important;");
                } else if ($(element).val() == '') {
                    form_validity = 'invalid';
                    $(element).parents('.jqte').attr("style", "border: 1px solid #ff0000 !important;");
                    $(element).attr("style", "border: 1px solid #ff0000 !important;");
                    animate_to = $(element).parent().parent();
                }
            } else {
                if ($(element).val() != '') {
                    $(element).css({"border": "1px solid #eceef4"});
                    $(element).parent().parent().parent().css({"border": "none"});
                } else if ($(element).val() == '') {
                    form_validity = 'invalid';
                    if ($(element).hasClass('wp_dp_editor')) {
                        $(element).parent().parent().parent().css({"border": "1px solid #ff0000"});
                        animate_to = $(element).parent().parent().parent();
                    } else {
                        $(element).css({"border": "1px solid #ff0000"});
                        animate_to = $(element);
                    }
                }
            }
            if (!is_already_animated) {
                if (animate_to != '') {
                    $('html, body').animate({scrollTop: $(animate_to).offset().top - 100}, 1000);
                    is_already_animated = true;
                }
            }
        }
        if ($(element).hasClass('usererror')) {
            form_validity = 'invalid';
            $(element).css({"border": "1px solid #ff0000"});
            animate_to = $(element);
        }
    });

    if (form_validity == 'valid') {
        return true;
    } else {
        return false;
    }
}

function getParameterByName(name) {
    "use strict";
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

$(document).on('click', '.listing-settings-nav > li.cond-listing-settings', function (e) {
    change_tab($(this).data('act'), e);
});

$(document).on('click', '.cond-listing-settings', function () {
    "use strict";

    var _this = $(this);
    var _this_act = _this.data('act');
    var _main_counter = _this.parents('ul').data('mcounter');
    var listing_id = _this.parents('ul').data('listing');
    var ajax_url = wp_dp_listing_strings.ajax_url;

    if (typeof _this_act !== 'undefined' && _this_act != '') {

        var this_action = 'listing_show_set_' + _this_act;
        wp_dp_show_loader('.loader-holder');
        $.ajax({
            url: ajax_url,
            method: "POST",
            data: {
                set_type: _this_act,
                _main_counter: _main_counter,
                listing_id: listing_id,
                action: this_action
            },
            dataType: "json"
        }).done(function (response) {
            $('#listing-sets-holder').html(response.html);
            chosen_selectionbox();
            var data_vals = 'tab=add-listing&listing_id=' + listing_id + '&listing_tab=' + _this_act;
            var current_url = location.protocol + "//" + location.host + location.pathname + "?" + data_vals;
            window.history.pushState(null, null, decodeURIComponent(current_url));
            wp_dp_hide_loader();
        }).fail(function () {
            wp_dp_hide_loader();
        });
    }
});

function listing_pkg_info_show(pkg_id, p_type, p_price) {
    var listing_doing = $("#listing-sets-holder").attr('data-doing');
    var package_tab = $('a[data-act="package"]');
    var appender = $('.wp-dp-dev-listing-pckg-info');
    $.ajax({
        url: wp_dp_listing_strings.ajax_url,
        method: "POST",
        data: {
            p_price: p_price,
            p_type: p_type,
            pkg_id: pkg_id,
            action: 'wp_dp_show_listing_pkg_info'
        },
        dataType: "json"
    }).done(function (response) {
        appender.html(response.html);
        
    if (response.show_pay == 'hide') {
            if (listing_doing == 'updating') {
                $('#register-listing-order').val(wp_dp_listing_strings.update_txt);
            } else {
                $('#register-listing-order').val(wp_dp_listing_strings.create_list_txt);
            }
            package_tab.html(wp_dp_listing_strings.update_txt);
            $('.register-payment-gw-holder').hide();
            $('.package-review-holder').hide();
            
            $('.payment-summary-fields').find('input, textarea').each(function() {
                jQuery(this).removeClass('wp-dp-dev-req-field');
            });
            
        } else {
            $('#register-listing-order').val(wp_dp_listing_strings.submit_order_txt);
            package_tab.html(wp_dp_listing_strings.payment_txt);
            $('.register-payment-gw-holder').show();
            $('.package-review-holder').show();
            
        }
    }).fail(function () {

    });
}

function listing_ajax_pkg_activation_msg(that) {
    var appender = $('.create-listing-holder');
    $.ajax({
        url: wp_dp_listing_strings.ajax_url,
        method: "POST",
        data: {
            p_pkg: 'ajax',
            action: 'wp_dp_show_pkg_activation_msg'
        },
        dataType: "json"
    }).done(function (response) {
        appender.append(response.html);
        jQuery('.user-add-listing').hide();
        jQuery('html,body').animate({
            scrollTop: jQuery('.create-listing-holder').offset().top - 300
        }, 500);
        var response = {
            type: 'success',
            msg: wp_dp_listing_strings.listing_created
        };
        wp_dp_show_response(response);
        that.prop('disabled', false);
    }).fail(function () {
        var response = {
            type: 'success',
            msg: wp_dp_listing_strings.listing_created
        };
        wp_dp_show_response(response);
        that.prop('disabled', false);
    });
}

// Used by Register and Add listing shortcode.
function add_event_listners(strings, $) {
    "use strict";
    $("select").trigger("chosen:updated");

    var listing_doing = $("#listing-sets-holder").attr('data-doing');

    $(document).on('click', '.dir-purchased-packages .dev-listing-pakcge-step', function (e) {
        var pkg_id = $(this).data('id');
        $('#package-' + pkg_id).prop('checked', true);
    });

    $(document).on('click', '.dev-listing-pakcge-step', function (e) {
        e.stopPropagation();
        var this_id = $(this).data('main-id');
        var pkg_id = $(this).data('id');

        var pkg_ptype = $(this).data('ptype');
        var pkg_ppric = $(this).data('ppric');
        var img_nums = $(this).data('picnum');
        var doc_nums = $(this).data('docnum');

        var is_form_valid = validate_register_add_listing_form($(this).parents('ul#wp-dp-dev-main-con-' + this_id));

        if (is_form_valid) {
            // update image nums
            $('.wp_dp_dev_listing_gallery_images').attr('data-count', img_nums);
            $('.upload-gallery-placeholder').find('p span').text(img_nums);
            //
            
            // update doc nums
            $('.wp_dp_listing_attachment_images').attr('data-count', doc_nums);
            $('.upload-attachments-placeholder').find('p span').text(doc_nums);
            //
            var package_tab = $('a[data-act="package"]');
            if ($("input[name='wp_dp_listing_package']:checked").length > 0 && ($("input[name='wp_dp_listing_package']:checked").parents('td').find('a').attr('data-ppric') == 'free')) {
                $("input[name='trans_first_name'], input[name='trans_last_name'], input[name='trans_email'], input[name='trans_phone_number'], textarea[name='trans_address']").removeClass('wp-dp-dev-req-field');
            } else {
                $("input[name='trans_first_name'], input[name='trans_last_name'], input[name='trans_email'], input[name='trans_phone_number'], textarea[name='trans_address']").addClass('wp-dp-dev-req-field');
            }

            if (($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0) && listing_doing != 'updating') {
                $('li[data-act="listing-detail-info"]').addClass('active processing');
                change_tab('listing-detail-info', e);
            } else if (($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0) && strings.is_listing_posting_free != "on" && listing_doing == 'updating') {
                package_tab.html(wp_dp_listing_strings.payment_txt);
                //$('.register-payment-gw-holder').show();
                //$('#register-listing-order').val(wp_dp_listing_strings.submit_order_txt);
                //$('li[data-act="listing-detail-info"]').addClass('active processing');
                //change_tab('listing-detail-info', e);
            } else {
                if (listing_doing == 'updating') {
                    package_tab.html(wp_dp_listing_strings.update_txt);
                    $('.register-payment-gw-holder').hide();
                    $('#register-listing-order').val(wp_dp_listing_strings.update_txt);
                    $('li[data-act="listing-detail-info"]').addClass('active processing');
                    change_tab('listing-detail-info', e);
                } else {
                    var response = {
                        type: 'error',
                        msg: strings.package_required_error
                    };
                    wp_dp_show_response(response);
                }
            }
            listing_pkg_info_show(pkg_id, pkg_ptype, pkg_ppric);
        }
        return false;
    });

    $("#btn-next-listing-information").click(function (e) {
        e.stopPropagation();
        var this_id = $(this).data('id');
        var is_form_valid = validate_register_add_listing_form($(this).parents('ul#wp-dp-dev-main-con-' + this_id));

        if (is_form_valid) {
            var package_tab = $('a[data-act="package"]');
            if (($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0) && listing_doing != 'updating') {
                $('li[data-act="listing-detail-info"]').addClass('active processing');
                change_tab('listing-detail-info', e);
            } else if (($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0) && strings.is_listing_posting_free != "on" && listing_doing == 'updating') {
                package_tab.html(wp_dp_listing_strings.payment_txt);
                $('.register-payment-gw-holder').show();
                $('#register-listing-order').val(wp_dp_listing_strings.submit_order_txt);
                $('li[data-act="listing-detail-info"]').addClass('active processing');
                change_tab('listing-detail-info', e);
            } else {
                if (listing_doing == 'updating') {
                    package_tab.html(wp_dp_listing_strings.update_txt);
                    $('.register-payment-gw-holder').hide();
                    $('#register-listing-order').val(wp_dp_listing_strings.update_txt);
                    $('li[data-act="listing-detail-info"]').addClass('active processing');
                    change_tab('listing-detail-info', e);
                } else {
                    var response = {
                        type: 'error',
                        msg: strings.package_required_error
                    };
                    wp_dp_show_response(response);
                }
            }
        }
        return false;
    });

    $(document).on('click', '.dev-listing-pakcge-login-step', function (e) {
        e.stopPropagation();
        var this_id = $(this).data('main-id');
        var pkg_id = $(this).data('id');
        var pkg_ptype = $(this).data('ptype');
        var pkg_ppric = $(this).data('ppric');

        var is_form_valid = validate_register_add_listing_form($(this).parents('ul#wp-dp-dev-main-con-' + this_id));

        var this_container = $(this).parents('ul#wp-dp-dev-main-con-' + this_id);

        listing_pkg_info_show(pkg_id, pkg_ptype, pkg_ppric);
        if (is_form_valid) {
            if ($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0 || strings.is_listing_posting_free == "on") {
                $('#sign-in').modal('show');
                $('#sign-in').find('div[id^="user-login-tab-"]').addClass('active in');
                $('#sign-in').find('div[id^="user-register-"]').removeClass('active in');
                var listing_type = this_container.find('input[name="wp_dp_listing_type"]:checked').val();
                var listing_categ = '';
                var listing_sub_categ = '';
                var listing_categ_list = this_container.find('ul.listing-cats-list').find('li');
                if (listing_categ_list.length > 0) {
                    listing_categ = this_container.find('ul.listing-cats-list').find('li input:checked').val();
                    if (this_container.find('.wp_dp_listing_category_field').find('select').length > 0) {
                        listing_sub_categ = this_container.find('.wp_dp_listing_category_field').find('select').val();
                    }
                }

                var listing_pkge = '';
                if (this_container.find('input[name="wp_dp_listing_package"]').length > 0) {
                    listing_pkge = this_container.find('input[name="wp_dp_listing_package"]:checked').val();
                }

                if (this_container.find('input[name="wp_dp_listing_active_package"]').length > 0) {
                    listing_pkge = this_container.find('input[name="wp_dp_listing_active_package"]:checked').val();
                }

                $.ajax({
                    url: wp_dp_listing_strings.ajax_url,
                    method: "POST",
                    data: {
                        login_type: 'create_listing',
                        login_listing_type: listing_type,
                        login_listing_categ: listing_categ,
                        login_listing_sub_categ: listing_sub_categ,
                        login_listing_pkge: listing_pkge,
                        action: 'wp_dp_create_listing_login'
                    },
                    dataType: "json"
                }).done(function () {});
            } else {
                var response = {
                    type: 'error',
                    msg: strings.package_required_error
                };
                wp_dp_show_response(response);
            }
        }
        return false;
    });

    $("#btn-next-user-login").click(function (e) {
        e.stopPropagation();
        var this_id = $(this).data('id');
        var is_form_valid = validate_register_add_listing_form($(this).parents('ul#wp-dp-dev-main-con-' + this_id));

        var this_container = $(this).parents('ul#wp-dp-dev-main-con-' + this_id);

        if (is_form_valid) {
            if ($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0 || strings.is_listing_posting_free == "on") {
                $('#sign-in').modal('show');
                $('#sign-in').find('div[id^="user-login-tab-"]').addClass('active in');
                $('#sign-in').find('div[id^="user-register-"]').removeClass('active in');
                var listing_type = this_container.find('input[name="wp_dp_listing_type"]:checked').val();
                var listing_categ = '';
                var listing_sub_categ = '';
                var listing_categ_list = this_container.find('ul.listing-cats-list').find('li');
                if (listing_categ_list.length > 0) {
                    listing_categ = this_container.find('ul.listing-cats-list').find('li input:checked').val();
                    if (this_container.find('.wp_dp_listing_category_field').find('select').length > 0) {
                        listing_sub_categ = this_container.find('.wp_dp_listing_category_field').find('select').val();
                    }
                }

                var listing_pkge = '';
                if (this_container.find('input[name="wp_dp_listing_package"]').length > 0) {
                    listing_pkge = this_container.find('input[name="wp_dp_listing_package"]:checked').val();
                }

                if (this_container.find('input[name="wp_dp_listing_active_package"]').length > 0) {
                    listing_pkge = this_container.find('input[name="wp_dp_listing_active_package"]:checked').val();
                }

                $.ajax({
                    url: wp_dp_listing_strings.ajax_url,
                    method: "POST",
                    data: {
                        login_type: 'create_listing',
                        login_listing_type: listing_type,
                        login_listing_categ: listing_categ,
                        login_listing_sub_categ: listing_sub_categ,
                        login_listing_pkge: listing_pkge,
                        action: 'wp_dp_create_listing_login'
                    },
                    dataType: "json"
                }).done(function () {});
            } else {
                var response = {
                    type: 'error',
                    msg: strings.package_required_error
                };
                wp_dp_show_response(response);
            }
        }
        return false;
    });

    $("#btn-back-listing-detail").click(function (e) {

        change_tab('listing-information', e);
        $('li[data-act="listing-detail-info"]').removeClass('active processing');
        return false;
    });

    $("#btn-next-listing-detail").click(function (e) {
        var this_id = $(this).data('id');
        var is_form_valid = validate_register_add_listing_form($(this).parents('ul#wp-dp-dev-main-con-' + this_id));

        if (is_form_valid) {
            change_tab('advance-options', e);
            $('li[data-act="advance-options"]').addClass('active processing');
        }
        return false;
    });

    $("#btn-back-advance-options").click(function (e) {

        change_tab('listing-detail-info', e);
        $('li[data-act="advance-options"]').removeClass('active processing');
        return false;
    });

    $("#btn-next-advance-options").click(function (e) {
        var this_id = $(this).data('id');
        var is_form_valid = validate_register_add_listing_form($(this).parents('ul#wp-dp-dev-main-con-' + this_id));

        if (is_form_valid) {
            change_tab('loc-address', e);
        }
        return false;
    });

    $("#btn-back-loc-address").click(function (e) {

        change_tab('advance-options', e);
        $('li[data-act="loc-address"]').removeClass('active processing');
        return false;
    });

    $("#btn-next-loc-address").click(function (e) {
        var this_id = $(this).data('id');
        var is_form_valid = validate_register_add_listing_form($(this).parents('ul#wp-dp-dev-main-con-' + this_id));

        if (is_form_valid) {
            change_tab('listing-photos', e);
        }
        return false;
    });

    $("#btn-back-listing-photos").click(function (e) {

        change_tab('loc-address', e);
        $('li[data-act="listing-photos"]').removeClass('active processing');
        return false;
    });

    $("#btn-next-listing-photos").click(function (e) {
        var this_id = $(this).data('id');
        var is_form_valid = validate_register_add_listing_form($(this).parents('ul#wp-dp-dev-main-con-' + this_id));

        if (is_form_valid) {
            if (strings.is_listing_posting_free == "on") {
                process_form_add_listing_and_register_user(strings, false, '');
            } else {
                change_tab('package', e);
            }
        }
        return false;
    });

    $("#btn-next-package").click(function (e) {
        e.stopPropagation();
        if ($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0 || strings.is_listing_posting_free == "on") {
            change_tab('payment-information', e);
        } else {
            var response = {
                type: 'error',
                msg: strings.package_required_error
            };
            wp_dp_show_response(response);
        }
        return false;
    });
    $(".wp-dp-dev-payment-form input[type='submit']").prop("disabled", false);
    
    
    //
    $(document).on('click', '#register-listing-order', function (e) {
        e.stopPropagation();
        if( jQuery('.listing-package-info-tab-container').length > 0 ){
            var payment_info_style = jQuery('.listing-package-info-tab-container').css("display");
            if( typeof payment_info_style !== "undefined" && payment_info_style == 'none' ){
                $('.listing-package-info-tab-container .payment-summary-fields').find('input, textarea').each(function() {
                    jQuery(this).removeClass('wp-dp-dev-req-field');
                });
            }
        }
        
        var is_form_valid = validate_register_add_listing_form($('form.wp-dp-dev-listing-form'));
        if (!is_form_valid) {
            return false;
        }
//        if (listing_doing == 'updating') {
//            if (jQuery("input[name='expired_listing']").length > 0 && jQuery("input[name='expired_listing']").val() === 'true' ) {
//                jQuery('.listing-detail-info-tab-container').hide();
//                jQuery('.listing-package-info-tab-container').show();
//                jQuery("input[name='expired_listing']").val('false');
//            }else{
//                process_add_listing_form_and_register_user(strings, true, '');
//            }
//            
//        }else{
            process_add_listing_form_and_register_user(strings, true, '');
        //}
        
        return false;
    });
    
    function process_add_listing_form_and_register_user( strings, package_verification, do_update ) {
        "use strict";
        var form_elem = $(".wp-dp-dev-payment-form");
        if (do_update == 'do_updating_btn') {
            that = $('.' + do_update);
        } else {
            var that = $("input[id='register-listing-order']");
            if (that.length < 1) {
                that = $("input[type='submit']:last");
            }
        }

        var old_value = that.val();
        if (do_update == 'do_updating_btn') {
            var thisObj = $('.listing-update-dashboard');
            wp_dp_show_loader('.listing-update-dashboard', '', 'button_loader', thisObj);
        } else {
            var thisObj = jQuery('.wp-dp-listing-submit-loader');
            wp_dp_show_loader('.wp-dp-listing-submit-loader', '', 'button_loader', thisObj);
        }
        that.prop('disabled', true);
        
        if (do_update == 'do_updating_btn') {
            var loadr_div = $('.' + do_update).parents('.listing-update-dashboard');
        } else {
            var loadr_div = jQuery('#register-listing-order').parents('.wp-dp-listing-submit-process');
        }

        loadr_div.addClass('active-ajax');

        var data = new FormData();

        if ($('.wp_dp_dev_listing_gallery_images').length) {
            var files = $('.wp_dp_dev_listing_gallery_images').prop('files');
            if (files.length > 0) {
                $.each($('.wp_dp_dev_listing_gallery_images'), function (i, obj) {
                    $.each(obj.files, function (j, file) {
                        data.append('wp_dp_listing_gallery_images[' + j + ']', file);
                    })
                });
            }
        }
        if ($('.wp_dp_listing_floor_images').length) {
            var floor_files = $('.wp_dp_listing_floor_images').prop('files');
            if (floor_files.length > 0) {
                $.each($('.wp_dp_listing_floor_images'), function (i, obj) {
                    $.each(obj.files, function (j, file) {
                        data.append('wp_dp_listing_floor_images[' + j + ']', file);
                    })
                });
            }
        }
        if ($('.wp_dp_listing_attachment_images').length) {
            var attach_files = $('.wp_dp_listing_attachment_images').prop('files');
            if (attach_files.length > 0) {
                $.each($('.wp_dp_listing_attachment_images'), function (i, obj) {
                    $.each(obj.files, function (j, file) {
                        data.append('wp_dp_listing_attachment_images[' + j + ']', file);
                    })
                });
            }
        }

        var other_data = $("form.wp-dp-dev-listing-form").serializeArray();
        $.each(other_data, function (key, input) {
            data.append(input.name, input.value);
        });

        $.ajax({
            url: wp_dp_listing_strings.ajax_url,
            method: "POST",
            data: data,
            processData: false,
            contentType: false,
            dataType: "json"
        }).done(function (response) {
            if (response.status == true) {
                if (listing_doing == 'updating') {
//                    if( response.pay_package == true ){
//                        if( response.package_price === 'free' ){
//                            var response = {
//                                type: 'success',
//                                msg: wp_dp_listing_strings.listing_updated
//                            };
//                            wp_dp_show_response(response);
//                            that.prop('disabled', false);
//                        }else{
//                            $("input[name='trans_id']").val(response.msg);
//                            var page_location = window.location + "";
//                            if (page_location.indexOf('?') > -1) {
//                                page_location += '&tab=activation';
//                            } else {
//                                page_location += '?tab=activation';
//                            }
//                            $("input[name='trans_id']").parent().append('<input type="hidden" name="transaction_return_url" value="' + page_location + '">');
//                            jQuery('.listing-detail-info-tab-container').hide();
//                            jQuery('.listing-package-info-tab-container').show();
//                        }
//                    }else 
                    if( jQuery("input[name='wp_dp_listing_new_package_used']:checked").length > 0 ){
                        var package_price = jQuery("input[name='wp_dp_listing_new_package_used']:checked").attr('data-ppric');
                        if( package_price === 'free' ){
                            var response = {
                                type: 'success',
                                msg: wp_dp_listing_strings.listing_updated
                            };
                            wp_dp_show_response(response);
                            that.prop('disabled', false);
                        }else{
                            $("input[name='trans_id']").val(response.msg);
                            var page_location = window.location + "";
                            if (page_location.indexOf('?') > -1) {
                                page_location += '&tab=activation';
                            } else {
                                page_location += '?tab=activation';
                            }
                            $("input[name='trans_id']").parent().append('<input type="hidden" name="transaction_return_url" value="' + page_location + '">');
                            jQuery('.listing-detail-info-tab-container').hide();
                            jQuery('.listing-package-info-tab-container').show();
                        }
                    }else if (jQuery("input[name='wp_dp_listing_active_package']:checked").length > 0) {
                        var response = {
                            type: 'success',
                            msg: wp_dp_listing_strings.listing_updated
                        };
                        wp_dp_show_response(response);
                        that.prop('disabled', false);
                    }else{
                        var response = {
                            type: 'success',
                            msg: wp_dp_listing_strings.listing_updated
                        };
                        wp_dp_show_response(response);
                        that.prop('disabled', false);
                    }
                }else{
                    if (jQuery("input[name='wp_dp_listing_active_package']:checked").length > 0) {
                        listing_ajax_pkg_activation_msg(that);
                    }else if( jQuery("input[name='wp_dp_listing_new_package_used']:checked").length > 0 ){
                        var package_price = jQuery("input[name='wp_dp_listing_new_package_used']:checked").attr('data-ppric');
                        if( package_price === 'free' ){
                            listing_ajax_pkg_activation_msg(that);
                        }else{
                            $("input[name='trans_id']").val(response.msg);
                            var page_location = window.location + "";
                            if (page_location.indexOf('?') > -1) {
                                page_location += '&tab=activation';
                            } else {
                                page_location += '?tab=activation';
                            }
                            $("input[name='trans_id']").parent().append('<input type="hidden" name="transaction_return_url" value="' + page_location + '">');
                            jQuery('.listing-detail-info-tab-container').hide();
                            jQuery('.listing-package-info-tab-container').show();
                        }
                    }
                }
                that.prop('disabled', false);
            } else {
                if (typeof response.msg != "undefined") {
                    var response = {
                        type: 'error',
                        msg: response.msg
                    };
                    wp_dp_show_response(response);
                    that.prop('disabled', false);
                    return false;
                }
            }
            loadr_div.removeClass('active-ajax');
        }).fail(function () {
            loadr_div.removeClass('active-ajax');
            return false;
        });
    }
    
    $(document).on('click', '#register-listing-package-order', function (e) {
        e.stopPropagation();
        var that = $("input[id='register-listing-package-order']");
        var old_value = that.val();
        var is_form_valid = validate_register_add_listing_form($('form.wp-dp-dev-listing-form'));
        if (!is_form_valid) {
            return false;
        }
        var thisObj = jQuery('.wp-dp-listing-package-submit-loader');
        wp_dp_show_loader('.wp-dp-listing-package-submit-loader', '', 'button_loader', thisObj);
        process_payment_form(old_value, that, $("form.wp-dp-dev-listing-form"));
        return false;
    });
    
    function process_payment_form(old_value, that, form_elem) {
        "use strict";
        var data = $(form_elem).serialize() + "&action=wp_dp_payment_gateways_package_selected";
        $.ajax({
            url: wp_dp_listing_strings.ajax_url,
            method: "POST",
            data: data,
            'dataType': "json"
        }).done(function (response) {
            if (response.status == true && response.payment_gateway == "wooCommerce") {
                jQuery("#listing-sets-holder").append(response.msg);
            } else {
                if (response.status == true) {
                    if (typeof response.payment_gateway != "undefined") {
                        if (response.payment_gateway == "WP_DP_PRE_BANK_TRANSFER") {
                            $(".wp-dp-dev-listing-form").html(response.msg);
                        } else {
                            $(".wp-dp-dev-listing-form").hide();
                            $(".payment-process-form-container").html(response.msg).find("form").submit();
                        }
                    }
                } else {
                    if (typeof response.msg != "undefined") {
                        jQuery.growl.error({
                            message: response.msg
                        });
                    }
                    $(that).html(old_value);
                    $(that).prop('disabled', false);
                }
            }
        }).fail(function () {
            $(that).html(old_value);
            $(that).prop('disabled', false);
        });
    }
    
    //
    

//    $(document).on('click', '#register-listing-order', function (e) {
//        e.stopPropagation();
//        var free_pkg = 'false';
//        if ($("input[name='wp_dp_listing_package']:checked").length > 0 && $("input[name='wp_dp_listing_package']:checked").parents('td').find('a').attr('data-ppric') == 'free') {
//            free_pkg = 'true';
//        } else if ($("input[name='wp_dp_listing_active_package']:checked").length > 0 && $("input[name='wp_dp_listing_active_package']:checked").parents('td').find('a').attr('data-ppric') == 'free') {
//            free_pkg = 'true';
//        } else if ($(".dir-purchased-packages input[name='wp_dp_listing_active_package']:checked").length > 0 && $(".dir-purchased-packages input[name='wp_dp_listing_active_package']:checked").attr('data-ppric') == 'free') {
//            free_pkg = 'true';
//        } else if ($("input[name='wp_dp_listing_package']:checked").length === 0 && $("input[name='wp_dp_listing_active_package']:checked").length === 0) {
//            free_pkg = 'true';
//        }
//        
//        // package selection check
//        if (($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0) && listing_doing != 'updating') {
//            
//        } else if (($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0) && strings.is_listing_posting_free != "on" && listing_doing == 'updating') {
//            
//        } else {
//            if (listing_doing == 'updating') {
//                
//            } else {
//                var response = {
//                    type: 'error',
//                    msg: strings.package_required_error
//                };
//                wp_dp_show_response(response);
//                return false;
//            }
//        }
//        //
//        
//        if( jQuery('.register-payment-gw-holder').length > 0 ){
//            var payment_info_style = jQuery('.register-payment-gw-holder').css("display");
//            if( typeof payment_info_style !== "undefined" && payment_info_style == 'none' ){
//                $('.payment-summary-fields').find('input, textarea').each(function() {
//                    jQuery(this).removeClass('wp-dp-dev-req-field');
//                });
//            }
//        }
//        
//        var is_form_valid = validate_register_add_listing_form($('form.wp-dp-dev-listing-form'));
//
//        if (!is_form_valid) {
//            return false;
//        }
//        
//        if (listing_doing == 'updating' && free_pkg != 'true') {
//            var returnType = wp_dp_validation_process(jQuery(".wp-dp-dev-payment-form"));
//            if (returnType == false) {
//                return false;
//            }
//        } else if (listing_doing != 'updating' && free_pkg != 'true') {
//            var returnType = wp_dp_validation_process(jQuery(".wp-dp-dev-payment-form"));
//            if (returnType == false) {
//                return false;
//            }
//        }
//        
//        process_form_add_listing_and_register_user(strings, true, '');
//
//        return false;
//    });

    $(document).on('click', '.do_updating_btn', function (e) {
        e.stopPropagation();

        process_form_add_listing_and_register_user(strings, true, 'do_updating_btn');

        return false;
    });

    function process_form_add_listing_and_register_user(strings, package_verification, do_update) {
        "use strict";
        var form_elem = $(".wp-dp-dev-payment-form");
        if (do_update == 'do_updating_btn') {
            that = $('.' + do_update);
        } else {
            var that = $("input[id='register-listing-order']");
            if (that.length < 1) {
                that = $("input[type='submit']:last");
            }
        }

        var old_value = that.val();
        if (do_update == 'do_updating_btn') {
            var thisObj = $('.listing-update-dashboard');
            wp_dp_show_loader('.listing-update-dashboard', '', 'button_loader', thisObj);
        } else {
            var thisObj = jQuery('.wp-dp-listing-submit-loader');
            wp_dp_show_loader('.wp-dp-listing-submit-loader', '', 'button_loader', thisObj);
        }
        that.prop('disabled', true);

        if (do_update == 'do_updating_btn') {
            var loadr_div = $('.' + do_update).parents('.listing-update-dashboard');
        } else {
            var loadr_div = jQuery('#register-listing-order').parents('.wp-dp-listing-submit-process');
        }

        loadr_div.addClass('active-ajax');

        var data = new FormData();

        if ($('.wp_dp_dev_listing_gallery_images').length) {
            var files = $('.wp_dp_dev_listing_gallery_images').prop('files');
            if (files.length > 0) {
                $.each($('.wp_dp_dev_listing_gallery_images'), function (i, obj) {
                    $.each(obj.files, function (j, file) {
                        data.append('wp_dp_listing_gallery_images[' + j + ']', file);
                    })
                });
            }
        }
        if ($('.wp_dp_listing_floor_images').length) {
            var floor_files = $('.wp_dp_listing_floor_images').prop('files');
            if (floor_files.length > 0) {
                $.each($('.wp_dp_listing_floor_images'), function (i, obj) {
                    $.each(obj.files, function (j, file) {
                        data.append('wp_dp_listing_floor_images[' + j + ']', file);
                    })
                });
            }
        }
        if ($('.wp_dp_listing_attachment_images').length) {
            var attach_files = $('.wp_dp_listing_attachment_images').prop('files');
            if (attach_files.length > 0) {
                $.each($('.wp_dp_listing_attachment_images'), function (i, obj) {
                    $.each(obj.files, function (j, file) {
                        data.append('wp_dp_listing_attachment_images[' + j + ']', file);
                    })
                });
            }
        }

        var other_data = $("form.wp-dp-dev-listing-form").serializeArray();
        $.each(other_data, function (key, input) {
            data.append(input.name, input.value);
        });

        $.ajax({
            url: wp_dp_listing_strings.ajax_url,
            method: "POST",
            data: data,
            processData: false,
            contentType: false,
            dataType: "json"
        }).done(function (response) {
            if (response.status == true) {
                if (package_verification == true) {
                    if (listing_doing == 'updating') {
                        if ($("input[name='wp_dp_listing_package']:checked").length > 0 || $("input[name='wp_dp_listing_active_package']:checked").length > 0) {
                            $("input[name='trans_id']").val(response.msg);
                            var page_location = window.location + "";
                            if (page_location.indexOf('?') > -1) {
                                page_location += '&tab=activation';
                            } else {
                                page_location += '?tab=activation';
                            }
                            $("input[name='trans_id']").parent().append('<input type="hidden" name="transaction_return_url" value="' + page_location + '">');
                            if ($("input[name='wp_dp_listing_active_package']:checked").length > 0) {
                                var response = {
                                    type: 'success',
                                    msg: wp_dp_listing_strings.listing_updated
                                };
                                wp_dp_show_response(response);
                                that.prop('disabled', false);
                            } else if ($("input[name='wp_dp_listing_package']:checked").length > 0 && ($("input[name='wp_dp_listing_package']:checked").parents('td').find('a').attr('data-ppric') == 'free')) {
                                var response = {
                                    type: 'success',
                                    msg: wp_dp_listing_strings.listing_updated
                                };
                                wp_dp_show_response(response);
                                that.prop('disabled', false);

                            } else {
                                process_payment_form(old_value, that, $("form.wp-dp-dev-listing-form"));
                            }
                        } else {
                            var response = {
                                type: 'success',
                                msg: wp_dp_listing_strings.listing_updated
                            };
                            wp_dp_show_response(response);
                            that.prop('disabled', false);
                        }
                    } else {
                        $("input[name='trans_id']").val(response.msg);
                        var page_location = window.location + "";
                        if (page_location.indexOf('?') > -1) {
                            page_location += '&tab=activation';
                        } else {
                            page_location += '?tab=activation';
                        }
                        $("input[name='trans_id']").parent().append('<input type="hidden" name="transaction_return_url" value="' + page_location + '">');
                        if ($("input[name='wp_dp_listing_active_package']:checked").length > 0) {
                            var response = {
                                type: 'success',
                                msg: wp_dp_listing_strings.listing_created
                            };
                            listing_ajax_pkg_activation_msg(that);
                        } else if ($("input[name='wp_dp_listing_package']:checked").length > 0 && ($("input[name='wp_dp_listing_package']:checked").parents('td').find('a').attr('data-ppric') == 'free')) {
                            listing_ajax_pkg_activation_msg(that);

                        } else {
                            process_payment_form(old_value, that, $("form.wp-dp-dev-listing-form"));
                        }
                    }
                } else {
                    change_tab('activation', undefined);
                }
                //wp_dp_show_response('', '', thisObj);
                that.prop('disabled', false);
            } else {
                if (typeof response.msg != "undefined") {
                    var response = {
                        type: 'error',
                        msg: response.msg
                    };
                    wp_dp_show_response(response);
                    that.prop('disabled', false);
                    return false;
                }
            }
            loadr_div.removeClass('active-ajax');
        }).fail(function () {
            loadr_div.removeClass('active-ajax');
            return false;
        });

    }
    
//    function process_payment_form(old_value, that, form_elem) {
//            "use strict";
//            var data = $(form_elem).serialize() + "&action=wp_dp_payment_gateways_package_selected";
//            $.ajax({
//                url: wp_dp_listing_strings.ajax_url,
//                method: "POST",
//                data: data,
//                'dataType': "json"
//            }).done(function (response) {
//                if (response.status == true && response.payment_gateway == "wooCommerce") {
//                    jQuery("#listing-sets-holder").append(response.msg);
//                } else {
//                    if (response.status == true) {
//                        if (typeof response.payment_gateway != "undefined") {
//                            if (response.payment_gateway == "WP_DP_PRE_BANK_TRANSFER") {
//                                $(".wp-dp-dev-listing-form").html(response.msg);
//                            } else {
//                                $(".wp-dp-dev-listing-form").hide();
//                                $(".payment-process-form-container").html(response.msg).find("form").submit();
//                            }
//                        }
//                    } else {
//                        if (typeof response.msg != "undefined") {
//                            jQuery.growl.error({
//                                message: response.msg
//                            });
//                        }
//                        $(that).html(old_value);
//                        $(that).prop('disabled', false);
//                    }
//                }
//            }).fail(function () {
//                $(that).html(old_value);
//                $(that).prop('disabled', false);
//            });
//        }

    $("#btn-back-package").click(function (e) {
        e.stopPropagation();
        change_tab('listing-photos', e);
        $('li[data-act="package"]').removeClass('active processing');
        return false;
    });

    $("#btn-back-payment-information").click(function (e) {
        e.stopPropagation();
        change_tab('package', e);
        return false;
    });
}

$(document).on('change', '.wp-dp-dev-username, .wp-dp-dev-user-email', function () {
    "use strict";
    var checkig_user,
            _this_ = $(this),
            _this_id = $(this).data('id'),
            _this_type = $(this).data('type'),
            _this_val = $(this).val(),
            ajax_url = wp_dp_listing_strings.ajax_url,
            _plugin_url = wp_dp_listing_strings.plugin_url,
            color,
            this_loader;

    if (_this_type == 'username') {
        this_loader = $('#wp-dp-dev-user-signup-' + _this_id).find('.wp-dp-dev-username-check');
    } else {
        this_loader = $('#wp-dp-dev-user-signup-' + _this_id).find('.wp-dp-dev-useremail-check');
    }

    this_loader.html('<div class="loader-holder" style="width:18px;"><img src="' + _plugin_url + 'assets/frontend/images/ajax-loader.gif" alt=""></div>');
    checkig_user = $.ajax({
        url: wp_dp_globals.ajax_url,
        method: "POST",
        data: {
            field_type: _this_type,
            field_val: _this_val,
            listing_add_counter: _this_id,
            action: 'wp_dp_listing_user_authentication'
        },
        dataType: "json"
    }).done(function (response) {
        if (typeof response.action !== 'undefined' && response.action == 'true') {
            color = 'green';
            _this_.css({"border": "1px solid #cccccc"});
            _this_.removeClass('usererror');
            _this_.removeClass('frontend-field-error');
        } else {
            color = 'red';
            _this_.css({"border": "1px solid #ff0000"});
            _this_.addClass('usererror');
        }
        if (typeof response.msg !== 'undefined' && response.msg != '') {
            this_loader.html('<em style="color:' + color + ';">' + response.msg + '</em>');
        } else {
            this_loader.html('<em style="color:' + color + ';">' + wp_dp_listing_strings.action_error + '</em>');
        }
    }).fail(function () {
        this_loader.html(wp_dp_listing_strings.action_error);
    });
});

if ($('.wp-dp-dev-username').length > 0 && $('.wp-dp-dev-username').val().length > 0) {
    $('.wp-dp-dev-username').trigger('change');
}

if ($('.wp-dp-dev-user-email').length > 0 && $('.wp-dp-dev-user-email').val().length > 0) {
    $('.wp-dp-dev-user-email').trigger('change');
}

$(document).on('click', '.cus-num-field .btn-decrementmin-num', function () {
    "use strict";
    var inp = $(this).parents('.cus-num-field').find('input');
    if (inp.val() > 0) {
        var new_val = parseInt(inp.val()) - 1;
        inp.val(new_val);
    } else {
        inp.val(0);
    }
});

$(document).on('click', '.cus-num-field .btn-incrementmin-num', function () {
    "use strict";
    var inp = $(this).parents('.cus-num-field').find('input');
    if ($.isNumeric(inp.val())) {
        var new_val = parseInt(inp.val()) + 1;
        inp.val(new_val);
    } else {
        inp.val(0);
    }

});

$(document).on('change', '.wp-dp-dev-select-type', function () {
    
    "use strict";
             var selecting_type,
            _this_id = $(this).data('id'),
            _pkgg_id = $(this).data('pkgg-id'),
            _this_val = $(this).val(),
            ajax_url = wp_dp_listing_strings.ajax_url,
            cf_append = $('#wp-dp-dev-cf-con'),
            tags_append = $('#wp-dp-proprty-tags-holder'),
            after_append = $('.wp-dp-dev-appended-cats'),
            price_append = $('.wp-dp-dev-appended-price'),
            features_append = $('.wp-dp-listing-features-holder'),
            pckgs_append = $('#listing-membership-info-main'),
            this_loader = $(this).parent('.type-holder-main');
            this_loader.addClass('active-ajax');
            wp_dp_show_loader("#cat-loader-"+_this_id+"");
     
    selecting_type = $.ajax({
        url: ajax_url,
        method: "POST",
        data: 'select_type=' + _this_val + '&p_listing_typ=' + _this_val + '&_pkgg_id=' + _pkgg_id + '&listing_add_counter=' + _this_id + '&action=wp_dp_listing_load_cf_cats',
        dataType: "json"
    }).done(function (response) {
        
        if (typeof response.features_html !== 'undefined') {
            features_append.html(response.features_html);
        }
        if (typeof response.cf_html !== 'undefined') {
            cf_append.html(response.cf_html);
        }
        if (typeof response.cats_html !== 'undefined') {
            after_append.html(response.cats_html);
        }
        if (typeof response.price_html !== 'undefined') {
            price_append.html(response.price_html);
        }
        if (typeof response.tags_html !== 'undefined') {
            //tags_append.html(response.tags_html);
        }
        if (response.detail_options.gallery == 'on') {
            //jQuery('#wp-dp-listing-gallery-holder').show();
        } else {
            //jQuery('#wp-dp-listing-gallery-holder').hide();
        }
        if (response.detail_options.allow_attachment_extensions !== '') {
            jQuery('#wp-dp-listing-attachments-holder ul.wp-dp-gallery-holder').attr('data-allow-ext', response.detail_options.allow_attachment_extensions);
        }
        if (response.detail_options.price == 'on') {
            jQuery('.listing-price-holder').show();
        } else {
            jQuery('.listing-price-holder').hide();
        }
        if (response.detail_options.special_price == 'on') {
            jQuery('#wp-dp-listing-special-price').show();
        } else {
            jQuery('#wp-dp-listing-special-price').hide();
        }
        if (response.detail_options.attachments == 'on') {
            jQuery('#wp-dp-listing-attachments-holder').show();
        } else {
            jQuery('#wp-dp-listing-attachments-holder').hide();
        }
        if (response.detail_options.yelp_places == 'on') {
            jQuery('#wp-dp-listing-yelp-places-holder').show();
        } else {
            jQuery('#wp-dp-listing-yelp-places-holder').hide();
        }
        if (response.detail_options.floor_plans == 'on') {
            jQuery('#wp-dp-listing-floor-plans-holder').show();
        } else {
            jQuery('#wp-dp-listing-floor-plans-holder').hide();
        }
        if (response.detail_options.appartments == 'on') {
            jQuery('#wp-dp-listing-appartments-holder').show();
        } else {
            jQuery('#wp-dp-listing-appartments-holder').hide();
        }
        if (response.detail_options.features == 'on') {
            jQuery('#wp-dp-listing-features-holder').show();
        } else {
            jQuery('#wp-dp-listing-features-holder').hide();
        }
        if (response.detail_options.video == 'on') {
            //jQuery('#wp-dp-listing-video-holder').show();
        } else {
            //jQuery('#wp-dp-listing-video-holder').hide();
        }
//        if (response.detail_options.opening_hours == 'on') {
//            jQuery('#wp-dp-listing-workings-days-holder').show();
//        } else {
//            jQuery('#wp-dp-listing-workings-days-holder').hide();
//        }
        if (response.detail_options.virtual_tour == 'on') {
            jQuery('#wp-dp-listing-virtual-tour-holder').show();
        } else {
            jQuery('#wp-dp-listing-virtual-tour-holder').hide();
        }
        if (response.detail_options.faqs == 'on') {
            //jQuery('#wp-dp-listing-faqs-holder').show();
        } else {
            //jQuery('#wp-dp-listing-faqs-holder').hide();
        }
        this_loader.removeClass('active-ajax');
         wp_dp_hide_loader();
    }).fail(function () {
        this_loader.removeClass('active-ajax');
         wp_dp_hide_loader();
    });
});

$(document).on('click', '.listing-pkg-select', function () {
    var _this_id = $(this).data('id');
    $('.pkg-selected').hide();
    $('#package-' + _this_id).prop('checked', true);
    $('#pkg-selected-' + _this_id).show();

});

$(document).on('click', '.listing-radios li', function () {
    var _this_ = $(this);
    _this_.parents('ul').find('li').removeAttr('class');
    _this_.addClass('active');
});

$(document).on('click', '.browse-attach-icon-img', function () {
    var _this_ = $(this);
    var this_id = _this_.attr('data-id');
    $('#browse-attach-icon-img-' + this_id).trigger('click');
});

$(document).on('click', '.browse-floor-icon-img', function () {
    var _this_ = $(this);
    var this_id = _this_.attr('data-id');
    $('#browse-floor-icon-img-' + this_id).trigger('click');
});


/*
 * Floor Plan add listing
 */
$(document).on('click', 'a[id^="wp-dp-dev-add-floor-plan-"]', function () {
    "use strict";

    var adding_service,
            _this_ = $(this),
            _this_id = $(this).data('id'),
            ajax_url = wp_dp_listing_strings.ajax_url,
            _plugin_url = wp_dp_listing_strings.plugin_url,
            _this_con = $('#wp-dp-dev-insert-floor-plan-con-' + _this_id),
            _title_field = _this_con.find('.floor-plan-title'),
            _desc_field = _this_con.find('.floor-plan-desc'),
            _this_append = $('#wp-dp-dev-add-floor-plan-app-' + _this_id),
            no_service_msg = _this_append.find('#no-floor-plan-' + _this_id),
            this_loader = $('#dev-floor-plans-loader-' + _this_id),
            this_act_msg = $('#wp-dp-dev-act-msg-' + _this_id);


    if (typeof _title_field !== 'undefined' && _title_field.val() != '') {

        var thisObj = jQuery('#wp-dp-dev-add-floor-plan-' + _this_id + '');
        wp_dp_show_loader('#wp-dp-dev-add-floor-plan-' + _this_id + '', '', 'button_loader', thisObj);


        var data = new FormData();
        $.each($("input.floor-plan-image")[0].files, function (key, value) {
            data.append(key, value);
        });
        data.append('title_field', _title_field.val());
        data.append('desc_field', _desc_field.val());
        data.append('listing_add_counter', _this_id);
        data.append('action', 'wp_dp_listing_floor_plan_to_list');
        adding_service = $.ajax({
            url: ajax_url,
            method: "POST",
            data: data,
            cache: false,
            dataType: "json",
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        }).done(function (response) {
            if (typeof response.html !== 'undefined') {
                no_service_msg.remove();
                _this_append.append(response.html);
                _title_field.val('');

                $('#wp-dp-dev-insert-floor-plan-con-' + _this_id).find('.icons-selector .selector .selected-icon > i').removeAttr('class');
                $('#wp-dp-dev-insert-floor-plan-con-' + _this_id).find('.icons-selector .selector .selected-icon > i').addClass('icon-cancel2');
                _desc_field.val('');
                _this_con.find('img.floor-plan-image-viewer').attr('src', '');
                _this_con.find('input.floor-plan-image').val('');
                $('#wp-dp-dev-insert-floor-plan-con-' + _this_id).find('.jqte_editor').html('');
            }
            jQuery('.service-list').find(".wp_dp_editor").jqte({
                'sub': false,
                'sup': false,
                'indent': false,
                'outdent': false,
                'unlink': false,
                'format': false,
                'color': false,
                'left': false,
                'right': false,
                'center': false,
                'strike': false,
                'rule': false,
                'fsize': false,
            });
            var response = {
                type: 'success',
                msg: wp_dp_listing_strings.ploor_plan_added
            };
            wp_dp_show_response(response, '', thisObj);
            $('#wp-dp-dev-insert-floor-plan-con-' + _this_id).slideUp();
        }).fail(function () {
            wp_dp_show_response('', '', thisObj);
        });
    } else {
        var response = {
            type: 'error',
            msg: wp_dp_listing_strings.compulsory_fields
        };
        wp_dp_show_response(response);
    }
});

$(document).on('change', 'input.floor-plan-image', function () {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('img.floor-plan-image-viewer').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
        $('.floor-plan-image-viewer-holder').show();
    }
});

$(document).on('click', '.remove-this-floor-plan', function () {
    $(this).parent('.info-holder').slideUp();
});

/*
 * End Floor Plan add listing
 */
 

/*
 * Add Near by 
 */

$(document).on('click', 'a[id^="wp-dp-dev-add-near-by-"]', function () {
    "use strict";
    var adding_service,
            _this_ = $(this),
            _this_id = $(this).data('id'),
            ajax_url = wp_dp_listing_strings.ajax_url,
            _plugin_url = wp_dp_listing_strings.plugin_url,
            _this_con = $('#wp-dp-dev-insert-near-by-con-' + _this_id),
            _title_field = _this_con.find('.near-by-title'),
            _desc_field = _this_con.find('.near-by-desc'),
            _this_append = $('#wp-dp-dev-add-near-by-app-' + _this_id),
            no_service_msg = _this_append.find('#no-near-by-' + _this_id),
            this_loader = $('#dev-near-by-loader-' + _this_id),
            this_act_msg = $('#wp-dp-dev-act-msg-' + _this_id);
    if (typeof _title_field !== 'undefined' && _title_field.val() != '') {

        var thisObj = jQuery('#wp-dp-dev-add-near-by-' + _this_id + '');
        wp_dp_show_loader('#wp-dp-dev-add-near-by-' + _this_id + '', '', 'button_loader', thisObj);

        var data = new FormData();
        $.each($("input.near-by-image")[0].files, function (key, value) {
            data.append(key, value);
        });
        data.append('title_field', _title_field.val());
        data.append('desc_field', _desc_field.val());
        data.append('listing_add_counter', _this_id);
        data.append('action', 'wp_dp_listing_near_by_to_list');
        adding_service = $.ajax({
            url: ajax_url,
            method: "POST",
            data: data,
            cache: false,
            dataType: "json",
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        }).done(function (response) {
            if (typeof response.html !== 'undefined') {
                no_service_msg.remove();
                _this_append.append(response.html);
                _title_field.val('');
                $('#wp-dp-dev-insert-near-by-con-' + _this_id).find('.icons-selector .selector .selected-icon > i').removeAttr('class');
                $('#wp-dp-dev-insert-near-by-con-' + _this_id).find('.icons-selector .selector .selected-icon > i').addClass('icon-cancel2');
                _desc_field.val('');
                _this_con.find('img.near-by-image-viewer').attr('src', '');
                _this_con.find('input.near-by-image').val('');
                $('#wp-dp-dev-insert-near-by-con-' + _this_id).find('.jqte_editor').html('');
            }

            var response = {
                type: 'success',
                msg: wp_dp_listing_strings.nearby_added
            };
            wp_dp_show_response(response, '', thisObj);
            $('#wp-dp-dev-insert-near-by-con-' + _this_id).slideUp();
        }).fail(function () {
            wp_dp_show_response('', '', thisObj);
        });
    } else {
        var response = {
            type: 'error',
            msg: wp_dp_listing_strings.compulsory_fields
        };
        wp_dp_show_response(response);
    }
});

$(document).on('change', 'input.near-by-image', function () {
    "use strict";
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('img.near-by-image-viewer')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(150);
        };

        reader.readAsDataURL(input.files[0]);
        $('img.near-by-image-viewer').show();
    }
});

$(document).on('click', '.remove-this-near-by', function () {
    $(this).parent('.info-holder').slideUp();
});

/*
 * End Near by
 */

//$(document).on('click', '.wp-dp-dev-insert-off-days .wp-dp-dev-calendar-days .day a', function () {
//    "use strict";
//    var adding_off_day,
//            _this_ = $(this),
//            _this_id = $(this).parents('.wp-dp-dev-insert-off-days').data('id'),
//            ajax_url = wp_dp_listing_strings.ajax_url,
//            _plugin_url = wp_dp_listing_strings.plugin_url,
//            _day = $(this).data('day'),
//            _month = $(this).data('month'),
//            _year = $(this).data('year'),
//            _adding_date = _year + '-' + _month + '-' + _day,
//            _add_date = true,
//            _this_append = $('#wp-dp-dev-add-off-day-app-' + _this_id),
//            no_off_day_msg = _this_append.find('#no-book-day-' + _this_id),
//            this_loader = $('#dev-off-day-loader-' + _this_id),
//            this_act_msg = $('#wp-dp-dev-act-msg-' + _this_id);
//
//    _this_append.find('li').each(function () {
//        var date_field = $(this).find('input[name^="wp_dp_listing_off_days"]');
//        if (_adding_date == date_field.val()) {
//            var response = {
//                type: 'success',
//                msg: wp_dp_listing_strings.off_day_already_added
//            };
//            wp_dp_show_response(response);
//            _add_date = false;
//        }
//    });
//    if (typeof _day !== 'undefined' && typeof _month !== 'undefined' && typeof _year !== 'undefined' && _add_date === true) {
//
//        var thisObj = jQuery('.book-btn');
//        wp_dp_show_loader('.book-btn', '', 'button_loader', thisObj);
//        adding_off_day = $.ajax({
//            url: ajax_url,
//            method: "POST",
//            data: {
//                off_day_day: _day,
//                off_day_month: _month,
//                off_day_year: _year,
//                listing_add_counter: _this_id,
//                action: 'wp_dp_listing_off_day_to_list'
//            },
//            dataType: "json"
//        }).done(function (response) {
//            if (typeof response.html !== 'undefined') {
//                no_off_day_msg.remove();
//                _this_append.append(response.html);
//                this_act_msg.html(wp_dp_listing_strings.off_day_added);
//            }
//            var response = {
//                type: 'success',
//                msg: wp_dp_listing_strings.off_day_added
//            };
//            wp_dp_show_response(response, '', thisObj);
//            $('#wp-dp-dev-cal-holder-' + _this_id).slideUp('fast');
//        }).fail(function () {
//            wp_dp_show_response('', '', thisObj);
//        });
//    }
//});

$(document).on('click', 'div[id^="wp-dp-dev-tag-info"] button', function () {
    var _this_id = $(this).data('id'),
            _this_tag_field = $('#wp-dp-dev-tag-info-' + _this_id).find('input'),
            _this_tag = $('#wp-dp-dev-tag-info-' + _this_id).find('input').val(),
            _this_append = $('#tag-cloud-' + _this_id),
            no_tag_msg = _this_append.find('#no-tag-' + _this_id),
            _this_tag_html = '<li class="tag-cloud">' + _this_tag + '<input type="hidden" name="wp_dp_tags[]" value="' + _this_tag + '"></li>';
    if (typeof _this_tag !== 'undefined' && _this_tag != '') {
        no_tag_msg.remove();
        _this_append.append(_this_tag_html);
        _this_tag_field.val('');
    }
});

$(document).on('click', 'a[id^="wp-dp-dev-open-time"]', function () {
    var _this_id = $(this).data('id'),
            _this_day = $(this).data('day'),
            _this_con = $('#open-close-con-' + _this_day + '-' + _this_id),
            _this_status = $('#wp-dp-dev-open-day-' + _this_day + '-' + _this_id);
    if (typeof _this_id !== 'undefined' && typeof _this_day !== 'undefined') {
        _this_status.val('on');
        _this_con.addClass('opening-time');
    }
});

$(document).on('click', 'a[id^="wp-dp-dev-close-time"]', function () {
    var _this_id = $(this).data('id'),
            _this_day = $(this).data('day'),
            _this_con = $('#open-close-con-' + _this_day + '-' + _this_id),
            _this_status = $('#wp-dp-dev-open-day-' + _this_day + '-' + _this_id);
    if (typeof _this_id !== 'undefined' && typeof _this_day !== 'undefined') {
        _this_status.val('');
        _this_con.removeClass('opening-time');
    }
});



$(document).on('click', 'a[id^="wp-dp-dev-floor-plan-edit-"]', function () {
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-floor-plan-edit-con-' + _this_id);

    _this_con.slideToggle();

    _this_con.find(".floor-plan-desc").jqte({
        'sub': false,
        'sup': false,
        'indent': false,
        'outdent': false,
        'unlink': false,
        'format': false,
        'color': false,
        'left': false,
        'right': false,
        'center': false,
        'strike': false,
        'rule': false,
        'fsize': false,
    });
});

$(document).on('click', 'a[id^="wp-dp-dev-near-by-edit-"]', function () {
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-near-by-edit-con-' + _this_id);

    _this_con.slideToggle();

});

$(document).on('click', 'a[id^="wp-dp-dev-insert-floor-plan-"]', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-insert-floor-plan-con-' + _this_id);

    _this_con.slideToggle();

    _this_con.find(".wp_dp_editor").jqte({
        'sub': false,
        'sup': false,
        'indent': false,
        'outdent': false,
        'unlink': false,
        'format': false,
        'color': false,
        'left': false,
        'right': false,
        'center': false,
        'strike': false,
        'rule': false,
        'fsize': false,
    });

});

$(document).on('click', 'a[id^="wp-dp-dev-insert-near-by-"]', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-insert-near-by-con-' + _this_id);

    _this_con.slideToggle();

    _this_con.find(".wp_dp_editor").jqte({
        'sub': false,
        'sup': false,
        'indent': false,
        'outdent': false,
        'unlink': false,
        'format': false,
        'color': false,
        'left': false,
        'right': false,
        'center': false,
        'strike': false,
        'rule': false,
        'fsize': false,
    });

});

$(document).on('click', 'a[id^="wp-dp-dev-floor-plan-save-"]', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-floor-plan-edit-con-' + _this_id),
            title_val = _this_con.find('.floor-plan-title').val();

    var thisObj = jQuery('#wp-dp-dev-floor-plan-save-' + _this_id + '');
    wp_dp_show_loader('#wp-dp-dev-floor-plan-save-' + _this_id + '', '', 'button_loader', thisObj);

    var input = _this_con.find('input[type="file"]')[0];
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            _this_con.find('img').attr('src', e.target.result);
            $('#floor-plan-image-' + _this_id + ' img').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
    $('#floor-plan-title-' + _this_id).html('<h6>' + title_val + '</h6>');

    setTimeout(function () {
        wp_dp_show_response('', '', thisObj);
        _this_con.slideUp();
    }, 500);
});

$(document).on('click', 'a[id^="wp-dp-dev-near-by-save-"]', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-near-by-edit-con-' + _this_id),
            title_val = _this_con.find('.near-by-title').val();

    var thisObj = jQuery('#wp-dp-dev-near-by-save-' + _this_id + '');
    wp_dp_show_loader('#wp-dp-dev-near-by-save-' + _this_id + '', '', 'button_loader', thisObj);

    var input = _this_con.find('input[type="file"]')[0];
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            _this_con.find('img').attr('src', e.target.result);
            $('#near-by-image-' + _this_id + ' img').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
    $('#near-by-title-' + _this_id).html('<h6>' + title_val + '</h6>');

    setTimeout(function () {
        wp_dp_show_response('', '', thisObj);
        _this_con.slideUp();
    }, 500);
});

$(document).on('click', '.book-btn', function () {
    //$(this).next('.calendar-holder').slideToggle("fast");
});

$(document).on('click', 'a[id^="wp-dp-dev-day-off-dp-"]', function () {
    var _this_id = $(this).data('id');
    $('#day-dpove-' + _this_id).remove();
});

$(document).on('click', 'a[id^="choose-all-apply-"]', function () {
    "use strict";
    var _this = $(this);
    var _this_id = $(this).data('id');

    if (_this.hasClass('feat-checked')) {
        _this.removeClass('feat-checked');
        $('#features-check-list-' + _this_id).find('input[type="checkbox"]').prop('checked', false);
    } else {
        _this.addClass('feat-checked');
        $('#features-check-list-' + _this_id).find('input[type="checkbox"]').prop('checked', 'checked');
    }
});

// Gallery btn
$(document).on('click', '.wp-dp-dev-gallery-upload-btn', function () {
    var _this_id = $(this).data('id'),
            this_triger = $('#image-uploader-' + _this_id);
    this_triger.trigger('click');
});

$(document).on('click', '.wp-dp-dev-attachment-upload-btn', function () {
    var _this_id = $(this).data('id'),
            this_triger = $('#attachment-uploader-' + _this_id);
    this_triger.trigger('click');
});

$(document).on('click', '.wp-dp-dev-floor-upload-btn', function () {
    var _this_id = $(this).data('id'),
            this_triger = $('#floor-uploader-' + _this_id);
    this_triger.trigger('click');
});

// Gallery btn
$(document).on('click', '.wp-dp-dev-featured-upload-btn', function () {
    var _this_id = $(this).data('id'),
            this_triger = $('#featured-image-uploader-' + _this_id);

    this_triger.trigger('click');
});
$(document).on('click', '.wp-dp-dev-video-cover-btn', function () {
    var _this_id = $(this).data('id'),
            this_triger = $('#video-image-uploader-' + _this_id);

    this_triger.trigger('click');
});

//add Gallery
function wp_dp_handle_file_single_select(event, counter) {
    "use strict";
    //Check File API support
    if (window.File && window.FileList && window.FileReader) {

        var files = event.target.files;
        var image_file = true;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            //Only pics
            if (!file.type.match('image')) {
                jQuery.growl.error({
                    message: wp_dp_listing_strings.upload_images_only
                });
                image_file = false;
            }

            if (image_file === true) {
                var picReader = new FileReader();
                picReader.addEventListener("load", function (event) {
                    var picFile = event.target;
                    if (picFile.result) {
                        console.log(picFile);
                        document.getElementById("featured-placeholder-" + counter).style.display = "none";
                        var listItems = jQuery('#wp-dp-dev-featured-img-' + counter + '').children().length;
                        if (listItems > 0) {
                            $('#wp-dp-dev-featured-img-' + counter + ' img').attr('src', picFile.result);
                            $('#wp-dp-dev-featured-img-' + counter + ' img').attr('title', picFile.name);
                            $('#wp-dp-dev-featured-img-' + counter + ' input').val('');
                        } else {
                            document.getElementById("wp-dp-dev-featured-img-" + counter).innerHTML += '\
                            <li class="gal-img">\
                                <div class="drag-list">\
                                    <div class="item-thumb"><img class="thumbnail" src="' + picFile.result + '" + "title="' + picFile.name + '"/></div>\
                                    <div class="item-assts">\
                                        <ul class="list-inline pull-right">\
                                            <li class="drag-btn"><a href="javascript:void(0);"><i class="icon-bars"></i></a></li>\
                                            <li class="close-btn"><a href="javascript:void(0);"><i class="icon-cross"></i></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </li>';
                        }
                    }
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
        }
    } else {
        jQuery.growl.error({
            message: "Your browser does not support File API"
        });
    }
}


// add video cover
function wp_dp_handle_file_single_select_video(event, counter) {
    "use strict";
    //Check File API support

    if (window.File && window.FileList && window.FileReader) {

        var files = event.target.files;
        var image_file = true;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            //Only pics
            if (!file.type.match('image')) {
                jQuery.growl.error({
                    message: wp_dp_listing_strings.upload_images_only
                });
                image_file = false;
            }

            if (image_file === true) {
                var picReader = new FileReader();
                picReader.addEventListener("load", function (event) {
                    var picFile = event.target;
                    if (picFile.result) {
                        console.log(picFile);
                        document.getElementById("video-placeholder-" + counter).style.display = "none";
                        var listItems = jQuery('#wp-dp-dev-video-img-' + counter + '').children().length;
                        if (listItems > 0) {
                            $('#wp-dp-dev-video-img-' + counter + ' img').attr('src', picFile.result);
                            $('#wp-dp-dev-video-img-' + counter + ' img').attr('title', picFile.name);
                            $('#wp-dp-dev-video-img-' + counter + ' input').val('');
                        } else {
                            document.getElementById("wp-dp-dev-video-img-" + counter).innerHTML += '\
                            <li class="gal-img">\
                                <div class="drag-list">\
                                    <div class="item-thumb"><img class="thumbnail" src="' + picFile.result + '" + "title="' + picFile.name + '"/></div>\
                                    <div class="item-assts">\
                                        <ul class="list-inline pull-right">\
                                            <li class="drag-btn"><a href="javascript:void(0);"><i class="icon-bars"></i></a></li>\
                                            <li class="close-btn"><a href="javascript:void(0);"><i class="icon-cross"></i></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </li>';
                        }
                    }
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
        }
    } else {
        jQuery.growl.error({
            message: "Your browser does not support File API"
        });
    }
}

//add Gallery
function wp_dp_handle_file_select(event, counter) {
    "use strict";
    //Check File API support
    if (window.File && window.FileList && window.FileReader) {

        var files = event.target.files;
        var image_file = true;

        var images_added_length = ($('#wp-dp-dev-gal-attach-sec-' + counter).find('li.gal-img').length) + files.length;
        var images_num_allowed = $('#image-uploader-' + counter).attr('data-count');
        if (images_num_allowed == 0) {
            var images_num_allowed = $('.dev-listing-pakcge-step').data('picnum');
            $('#image-uploader-' + counter).attr('data-count', images_num_allowed);
        }
        if (images_added_length <= images_num_allowed) {
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                //Only pics
                if (!file.type.match('image')) {
                    jQuery.growl.error({
                        message: wp_dp_listing_strings.upload_images_only
                    });
                    image_file = false;
                }

                if (image_file === true) {
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function (event) {
                        var picFile = event.target;
                        if (picFile.result) {
                            document.getElementById("wp-dp-dev-gal-attach-sec-" + counter).innerHTML = '\
                            <li class="gal-img">\
                                <div class="drag-list">\
                                    <div class="item-thumb"><img class="thumbnail" src="' + picFile.result + '" alt=""/></div>\
                                    <div class="item-assts">\
                                        <div class="list-inline pull-right">\
                                            <div class="close-btn" data-id="' + counter + '"><a href="javascript:void(0);"><i class="icon-cross"></i></a></div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </li>' + document.getElementById("wp-dp-dev-gal-attach-sec-" + counter).innerHTML;
                            
                            jQuery('#upload-gallery-placeholder-' + counter).hide();
                        }
                        $('#wp-dp-dev-gal-attach-sec-' + counter).sortable({
                            handle: '.drag-list',
                            cursor: 'move'
                        });
                    });
                    //Read the image
                    picReader.readAsDataURL(file);
                }
            }
        } else {
            jQuery.growl.error({
                message: wp_dp_listing_strings.more_than_f + " " + images_num_allowed + " " + wp_dp_listing_strings.more_than_image_change
            });
        }
    } else {
        jQuery.growl.error({
            message: "Your browser does not support File API"
        });
    }
}

function wp_dp_handle_attach_file_select(event, counter) {
    "use strict";
    //Check File API support
    if (window.File && window.FileList && window.FileReader) {

        var files = event.target.files;
        var docs_added_length = ($('#wp-dp-dev-docs-attach-sec-' + counter).find('li.gal-img').length) + files.length;
        var docs_num_allowed = $('#attachment-uploader-' + counter).attr('data-count');
        
        if (docs_num_allowed == 0) {
            var docs_num_allowed = $('.dev-listing-pakcge-step').data('docnum');
            $('#attachment-uploader-' + counter).attr('data-count', docs_num_allowed);
        }
        
        var allowd_extnx_erer = $("#wp-dp-dev-docs-attach-sec-" + counter).data('ext-error');
        var allowd_extnx = $("#wp-dp-dev-docs-attach-sec-" + counter).data('allow-ext');
        allowd_extnx = allowd_extnx.split(",");

        var is_error = false;

        if (docs_added_length <= docs_num_allowed) {

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var file_name = file.name;
                var file_ext = file_name.split(".").pop().toLowerCase();
                if (jQuery.inArray(file_ext, allowd_extnx) == -1) {
                    is_error = true;
                } else {
                    var timestamp = Math.floor((Math.random() * 999999) + 99);
                        var popup_html = '<div class="modal fade modal-form" id="add-attachment-data-' + timestamp + '" tabindex="-1" role="dialog">\
                        <div class="modal-dialog" role="document">\
                            <div class="modal-content">\
                                <div class="modal-header">\
                                    <button type="button" class="close close-faq" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
                                    <h4 class="modal-title" id="faqModalLabel">' + wp_dp_listing_strings.wp_dp_edit_details + '</h4>\
                                </div>\
                                <div class="modal-body">\
                                    <div class="row">\
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\
                                            <div class="field-holder">\
                                                <input type="text" placeholder="Title *" class="form-control" id="wp_dp_listing_attachment_title" name="wp_dp_listing_attachment_title[]"></div>\
                                        </div>\
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\
                                            <div class="field-holder faq-request-holder input-button-loader">\
                                                <input type="button" class="bgcolor wp_dp_add_attachment_data" name="add_attachment_data" data-id="' + timestamp + '" value="' + wp_dp_listing_strings.wp_dp_edit_details_update + '"></div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>';
                    var img_thumb = wp_dp_listing_strings.plugin_url + '/assets/common/attachment-images/attach-' + file_ext + '.png';
                    document.getElementById("wp-dp-dev-docs-attach-sec-" + counter).innerHTML = '\
                    <li class="gal-img">\
                        <div class="drag-list">\
                            <div class="item-thumb"><a data-target="#add-attachment-data-' + timestamp + '" data-toggle="modal" class="edit-attachment-btn  edit-btn-link" href="javascript:void(0);"><i class="icon-mode_edit"></i></a><img class="thumbnail" src="' + img_thumb + '" alt=""/>\
                            <div class="attachment-data-link-' + timestamp + ' block-popup-data"></div>\
                            </div>\
                            <div class="item-assts">\
                                <div class="list-inline pull-right">\
                                    <div class="close-btn" data-id="' + counter + '"><a href="javascript:void(0);"><i class="icon-cross"></i></a></div>\
                                </div>\
                            </div>\
                        </div>\
                    ' + popup_html + '</li>' + document.getElementById("wp-dp-dev-docs-attach-sec-" + counter).innerHTML;

                    $('#wp-dp-dev-docs-attach-sec-' + counter).sortable({
                        handle: '.drag-list',
                        cursor: 'move',
                        items: '.gal-img',
                    });
                }
            }
            if (is_error === true) {
                jQuery.growl.error({
                    message: allowd_extnx_erer
                });
                return false;
            }
        } else {
            jQuery.growl.error({
                message: wp_dp_listing_strings.more_than_f + " " + docs_num_allowed + " " + wp_dp_listing_strings.more_than_doc_change
            });
        }
    } else {
        jQuery.growl.error({
            message: "Your browser does not support File API"
        });
    }
}

//add Gallery
function wp_dp_handle_floor_file_select(event, counter) {
    "use strict";
    //Check File API support
    if (window.File && window.FileList && window.FileReader) {

        var files = event.target.files;
        var image_file = true;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            //Only pics
            if (!file.type.match('image')) {
                jQuery.growl.error({
                    message: wp_dp_listing_strings.upload_images_only
                });
                image_file = false;
            }

            if (image_file === true) {
                var picReader = new FileReader();
                picReader.addEventListener("load", function (event) {
                    var picFile = event.target;
                    if (picFile.result) {
                        var timestamp = Math.floor(event.timeStamp);
                        var popup_html = '<div class="modal fade modal-form" id="add-floor-image-data-' + timestamp + '" tabindex="-1" role="dialog">\
                <div class="modal-dialog" role="document">\
                    <div class="modal-content">\
                        <div class="modal-header">\
                            <button type="button" class="close close-faq" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
                            <h4 class="modal-title" id="faqModalLabel">' + wp_dp_listing_strings.wp_dp_edit_details + '</h4>\
                        </div>\
                        <div class="modal-body">\
                            <div class="row">\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\
                                    <div class="field-holder">\
                                        <input type="text" placeholder="Title *" class="form-control" id="wp_dp_listing_floor_plan_title" name="wp_dp_listing_floor_plan_title[]"></div>\
                                </div>\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\
                                    <div class="field-holder">\
                                        <textarea placeholder="Description *" class="form-control" rows="5" cols="30" id="wp_dp_listing_floor_plan_desc" name="wp_dp_listing_floor_plan_desc[]"></textarea></div>\
                                </div>\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\
                                    <div class="field-holder faq-request-holder input-button-loader">\
                                        <input type="button" class="bgcolor wp_dp_add_floor_plan_data" name="add_floor_plan_data" data-id="' + timestamp + '" value="' + wp_dp_listing_strings.wp_dp_edit_details_update + '"></div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>';
                        document.getElementById("wp-dp-dev-floor-attach-sec-" + counter).innerHTML = '\
                        <li class="gal-img">\
                            <div class="drag-list">\
                                <div class="item-thumb">\n\
                                <a data-target="#add-floor-image-data-' + timestamp + '" data-toggle="modal" class="edit-floor-data-btn edit-btn-link" href="javascript:void(0);"><i class="icon-mode_edit"></i></a><img class="thumbnail" src="' + picFile.result + '" alt=""/>\
                                <div class="add-floor-data-link-' + timestamp + ' block-popup-data"></div>\
                                </div>\
                                <div class="item-assts">\
                                    <div class="list-inline pull-right">\
                                        <div class="close-btn" data-id="' + counter + '"><a href="javascript:void(0);"><i class="icon-cross"></i></a></div>\
                                    </div>\
                                </div>\
                            </div>\
                        ' + popup_html + '</li>' + document.getElementById("wp-dp-dev-floor-attach-sec-" + counter).innerHTML;
                    }
                    $('#wp-dp-dev-floor-attach-sec-' + counter).sortable({
                        handle: '.drag-list',
                        cursor: 'move'
                    });
                });
                //Read the image
                picReader.readAsDataURL(file);
            }
        }
    } else {
        jQuery.growl.error({
            message: "Your browser does not support File API"
        });
    }
}


$(document).on('click', '.wp_dp_add_floor_plan_data', function () {
    var data_id = jQuery(this).data('id');
    var title = jQuery(this).closest('.modal-body').find('#wp_dp_listing_floor_plan_title').val();
    jQuery(".add-floor-data-link-"+data_id).html(title);
    $('#add-floor-image-data-'+data_id).modal('hide');
});

$(document).on('click', '.wp_dp_add_attachment_data', function () {
    var data_id = jQuery(this).data('id');
    var title = jQuery(this).closest('.modal-body').find('#wp_dp_listing_attachment_title').val();
    jQuery(".attachment-data-link-"+data_id).html(title);
    $('#add-attachment-data-'+data_id).modal('hide');
});


$(document).on('click', '.gal-img .close-btn', function () {
    var this_id = $(this).attr('data-id');
    $(this).parents('.gal-img').remove();
    $('.wp-dp-dev-gallery-uploader').val('');
    
//    var count_gallery_images = jQuery('#wp-dp-dev-gal-attach-sec-'+ this_id).find('li.gal-img').length;
//    if( count_gallery_images < 1 ){
//        jQuery('#upload-gallery-placeholder-'+ this_id).show();
//    }
//    
//    var count_attachments = jQuery('#upload-attachments-placeholder-'+ this_id).find('li.gal-img').length;
//    if( count_attachments < 1 ){
//        jQuery('#upload-attachments-placeholder-'+ this_id).show();
//    }
//    
//    var count_floor_images = jQuery('#wp-dp-dev-floor-attach-sec-'+ this_id).find('li.gal-img').length;
//    if( count_floor_images < 1 ){
//        jQuery('#upload-floor-placeholder-'+ this_id).show();
//    }
    
});

// Listing package update button
$(document).on('click', '.dev-wp-dp-listing-update-package', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            listing_info_con = $('#listing-info-sec-' + _this_id),
            packages_con = $('#listing-packages-sec-' + _this_id);

    listing_info_con.hide();
    packages_con.slideDown();
});

// Listing package update Cancel button
$(document).on('click', '.wp-dp-dev-cancel-pkg', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            listing_info_con = $('#listing-info-sec-' + _this_id),
            _check_new_btn = $('#wp-dp-dev-new-pkg-checkbox-' + _this_id),
            _new_pkgs_con = $('#new-packages-' + _this_id),
            _active_pkgs_con = $('#purchased-packages-' + _this_id),
            _purchased_pkg_head = $('#purchased-package-head-' + _this_id),
            _new_pkg_head = $('#buy-package-head-' + _this_id),
            packages_con = $('#listing-packages-sec-' + _this_id);

    listing_info_con.slideDown();
    packages_con.hide();
    $('.all-pckgs-sec').find('.pkg-detail-btn input[type="radio"]').prop('checked', false);
    $('.all-pckgs-sec').find('input[name="wp_dp_listing_featured"]').prop('checked', false);
    $('.all-pckgs-sec').find('input[name="wp_dp_listing_top_cat"]').prop('checked', false);
    $('.all-pckgs-sec').find('.package-info-sec').hide();
    $('.all-pckgs-sec').find('.wp-dp-pkg-header').removeClass('active-pkg');

    if ($('.dir-switch-packges-btn').length === 1) {
        var btn_switch = $('#wp-dp-dev-new-pkg-btn-' + _this_id);
        _check_new_btn.prop('checked', false);
        _new_pkgs_con.hide();
        _active_pkgs_con.slideDown();

        _new_pkg_head.hide();
        _purchased_pkg_head.slideDown();

        btn_switch.html(wp_dp_listing_strings.buy_new_packg);
    }

});

// Package detail Click
$(document).on('click', '.wp-dp-dev-detail-pkg', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            package_detail_sec = $('#package-detail-' + _this_id);

    if (!package_detail_sec.is(':visible')) {
        $('.all-pckgs-sec').find('.package-info-sec').hide();
        package_detail_sec.slideDown();
        $(this).html(wp_dp_listing_strings.close_txt);
    } else {
        package_detail_sec.slideUp();
        $(this).html(wp_dp_listing_strings.detail_txt);
    }

});

// Package check Click
$(document).on('click', '.pkg-detail-btn .check-select', function () {
    "use strict";
    var input_radio = $(this).parents('.pkg-detail-btn').find('input[type="radio"]');
    if (!input_radio.is(':checked')) {
        $(this).parents('.wp-dp-pkg-header').addClass('active-pkg');
        input_radio.prop('checked', true);
    }

});

// Package cancel detail Click
$(document).on('click', '.pkg-cancel-btn', function () {
    "use strict";
    var _this = $(this),
            _this_id = $(this).data('id'),
            package_detail_sec = $('#package-detail-' + _this_id),
            package_btn = $('#package-' + _this_id);

    package_detail_sec.slideUp(400, function () {
        _this.parents('.wp-dp-pkg-holder').find('.wp-dp-pkg-header').removeClass('active-pkg');
    });
    package_btn.prop('checked', false);
    $('.register-payment-gw-holder').slideUp();
    $('#register-listing-order').hide();
    _this.parents('.wp-dp-pkg-holder').find('input[name="wp_dp_listing_featured"]').prop('checked', false);
    _this.parents('.wp-dp-pkg-holder').find('input[name="wp_dp_listing_top_cat"]').prop('checked', false);
});

// Package Select Submit Click
$(document).on('click', '.pkg-choose-btn', function () {
    "use strict";
    var _this = $(this),
            _this_id = _this.data('id'),
            package_detail_sec = $('#package-detail-' + _this_id),
            package_btn = $('#package-' + _this_id);
    $('.all-pckgs-sec').find('.wp-dp-pkg-header').removeClass('active-pkg');

    package_btn.prop('checked', true);
    $('.register-payment-gw-holder').slideDown();
    $('#register-listing-order').show();
    package_detail_sec.slideUp(400, function () {
        _this.parents('.wp-dp-pkg-holder').find('.wp-dp-pkg-header').addClass('active-pkg');
    });
});

$(document).on('click', 'a[id^="wp-dp-dev-new-pkg-btn-"]', function () {
    "use strict";
    var _this = $(this),
            _this_id = $(this).data('id'),
            _check_new_btn = $('#wp-dp-dev-new-pkg-checkbox-' + _this_id),
            _new_pkgs_con = $('#new-packages-' + _this_id),
            _active_pkgs_con = $('#purchased-packages-' + _this_id),
            _purchased_pkg_head = $('#purchased-package-head-' + _this_id),
            _new_pkg_head = $('#buy-package-head-' + _this_id),
            _featured_top_checks = $('.dev-listing-featured-top-cat');

    _featured_top_checks.remove();
    if (_check_new_btn.is(':checked')) {
        _check_new_btn.prop('checked', false);
    } else {
        _check_new_btn.prop('checked', true);
    }

    if (_check_new_btn.is(':checked')) {
        _active_pkgs_con.hide();
        _new_pkgs_con.slideDown();

        _purchased_pkg_head.hide();
        _new_pkg_head.slideDown();

        _this.html(wp_dp_listing_strings.buy_exist_packg);
    } else {
        _new_pkgs_con.hide();
        _active_pkgs_con.slideDown();

        _new_pkg_head.hide();
        _purchased_pkg_head.slideDown();

        _this.html(wp_dp_listing_strings.buy_new_packg);
    }
});


/*
 * Add Attachemts 
 */

$(document).on('click', 'a[id^="wp-dp-dev-insert-attachments-"]', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-insert-attachments-con-' + _this_id);
    _this_con.slideToggle();
});

$(document).on('click', 'a[id^="wp-dp-dev-add-attachments-"]', function () {
    "use strict";
    var adding_service,
            _this_ = $(this),
            _this_id = $(this).data('id'),
            ajax_url = wp_dp_listing_strings.ajax_url,
            _this_con = $('#wp-dp-dev-insert-attachments-con-' + _this_id),
            _title_field = _this_con.find('.attachment-title'),
            _file_field = _this_con.find('.attachment-file'),
            _this_append = $('#wp-dp-dev-add-attachments-app-' + _this_id),
            no_attachments_msg = _this_append.find('#no-attachments-' + _this_id),
            this_act_msg = $('#wp-dp-dev-act-msg-' + _this_id);
    if (typeof _title_field !== 'undefined' && _title_field.val() != '' && typeof _file_field !== 'undefined' && _file_field.val() != '') {

        var thisObj = jQuery('#wp-dp-dev-add-attachments-' + _this_id + '');
        wp_dp_show_loader('#wp-dp-dev-add-attachments-' + _this_id + '', '', 'button_loader', thisObj);

        var data = new FormData();
        $.each($("input.attachment-file")[0].files, function (key, value) {
            data.append(key, value);
        });
        data.append('title_field', _title_field.val());
        data.append('allowed_extentions', _file_field.attr('data-allowed-extentions'));
        data.append('listing_add_counter', _this_id);
        data.append('action', 'wp_dp_listing_attachments_to_list');
        adding_service = $.ajax({
            url: ajax_url,
            method: "POST",
            data: data,
            cache: false,
            dataType: "json",
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        }).done(function (response) {
            if (typeof response.html !== 'undefined') {
                no_attachments_msg.remove();
                _this_append.append(response.html);
                _title_field.val('');
                $('#wp-dp-dev-insert-attachments-con-' + _this_id).find('.icons-selector .selector .selected-icon > i').removeAttr('class');
                $('#wp-dp-dev-insert-attachments-con-' + _this_id).find('.icons-selector .selector .selected-icon > i').addClass('icon-cancel2');
                _this_con.find('img.attachment-file-viewer').attr('src', '');
                _this_con.find('input.attachment-file').val('');
            }

            var response = {
                type: 'success',
                msg: wp_dp_listing_strings.attachment_added
            };
            wp_dp_show_response(response, '', thisObj);
            $('#wp-dp-dev-insert-attachments-con-' + _this_id).slideUp();
            $('.attachment-file-viewer-holder').hide();
        }).fail(function () {
            wp_dp_show_response('', '', thisObj);
        });
    } else {
        var response = {
            type: 'error',
            msg: wp_dp_listing_strings.compulsory_fields
        };
        wp_dp_show_response(response);
    }
});

$(document).on('change', 'input.attachment-file', function () {
    "use strict";
    var input = this;
    var this_id = jQuery(input).data('id');
    if (typeof this_id !== 'undefined') {
        jQuery('span.allowed-extensions-' + this_id).css('color', '');
    } else {
        jQuery('span.allowed-extensions').css('color', '');
    }
    if (input.files && input.files[0]) {
        var allowed_extentions = jQuery(input).attr('data-allowed-extentions');
        var new_allowed_extentions = new Array();
        new_allowed_extentions = allowed_extentions.split(",");
        var ext = input.value.match(/\.(.+)$/)[1];
        if ($.inArray(ext, new_allowed_extentions) == -1) {
            var response = {
                type: 'error',
                msg: 'Invalid Extension!'
            };
            if (typeof this_id !== 'undefined') {
                jQuery('span.allowed-extensions-' + this_id).css('color', 'red');
            } else {
                jQuery('span.allowed-extensions').css('color', 'red');
            }
            wp_dp_show_response(response);
            jQuery('.attachment-file').val('');
            $('.attachment-file-viewer-holder').hide();
            $('img.attachment-file-viewer').attr('src', '');
            return false;
        } else {
            if (typeof this_id !== 'undefined') {
                $('img.attached-file-' + this_id).attr('src', wp_dp_listing_strings.plugin_url + '/assets/common/attachment-images/attach-' + ext + '.png');
            } else {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('img.attachment-file-viewer')
                            .attr('src', wp_dp_listing_strings.plugin_url + '/assets/common/attachment-images/attach-' + ext + '.png');
                };
                reader.readAsDataURL(input.files[0]);
                $('.attachment-file-viewer-holder').show();
            }
        }
    }
});
$(document).on('click', 'a[id^="wp-dp-dev-attachments-edit-"]', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-attachments-edit-con-' + _this_id);
    _this_con.slideToggle();
});

$(document).on('click', 'a[id^="wp-dp-dev-attachments-save-"]', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-attachments-edit-con-' + _this_id),
            title_val = _this_con.find('.attachment-title').val();
    var file_val = _this_con.find('.attachment-file').val();

    var thisObj = jQuery('#wp-dp-dev-attachments-save-' + _this_id + '');
    wp_dp_show_loader('#wp-dp-dev-attachments-save-' + _this_id + '', '', 'button_loader', thisObj);

    var input = _this_con.find('input[type="file"]')[0];
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var ext = input.value.match(/\.(.+)$/)[1];
        reader.onload = function (e) {
            _this_con.find('img').attr('src', wp_dp_listing_strings.plugin_url + '/assets/common/attachment-images/attach-' + ext + '.png');
            $('#attachment-file-' + _this_id + ' img').attr('src', wp_dp_listing_strings.plugin_url + '/assets/common/attachment-images/attach-' + ext + '.png');
        };
        reader.readAsDataURL(input.files[0]);
    }
    $('#attachment-title-' + _this_id).html('<h6>' + title_val + '</h6>');

    var data = new FormData();
    $.each(_this_con.find('input.attachment-file')[0].files, function (key, value) {
        data.append(key, value);
    });
    data.append('ajax_filter', 'true');
    data.append('action', 'get_atachment_id_by_file');
    adding_service = $.ajax({
        url: wp_dp_listing_strings.ajax_url,
        method: "POST",
        data: data,
        cache: false,
        dataType: "json",
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
    }).done(function (response) {
        _this_con.find('.listing-attachment-id').val(response.html);
    })

    setTimeout(function () {
        wp_dp_show_response('', '', thisObj);
        _this_con.slideUp();
    }, 500);
});

$(document).on('click', '.remove-this-attachment', function () {
    $(this).parent('.info-holder').slideUp();
});

/*
 * Add Apartment 
 */

$(document).on('click', 'a[id^="wp-dp-dev-add-apartment-"]', function () {
    "use strict";
    var adding_service,
            _this_ = $(this),
            _this_id = $(this).data('id'),
            ajax_url = wp_dp_listing_strings.ajax_url,
            _plugin_url = wp_dp_listing_strings.plugin_url,
            _this_con = $('#wp-dp-dev-insert-apartment-con-' + _this_id),
            _plot_field = _this_con.find('.apartment-plot'),
            _beds_field = _this_con.find('.apartment-beds'),
            _price_field = _this_con.find('.apartment-price-from'),
            _floor_field = _this_con.find('.apartment-floor'),
            _address_field = _this_con.find('.apartment-address'),
            _availability_field = _this_con.find('.apartment-availability'),
            _link_field = _this_con.find('.apartment-link'),
            _this_append = $('#wp-dp-dev-add-apartment-app-' + _this_id),
            no_service_msg = _this_append.find('#no-apartment-' + _this_id),
            this_loader = $('#dev-apartment-loader-' + _this_id),
            this_act_msg = $('#wp-dp-dev-act-msg-' + _this_id);
    if (typeof _plot_field !== 'undefined' && _plot_field.val() != '') {

        var thisObj = jQuery('#wp-dp-dev-add-apartment-' + _this_id + '');
        wp_dp_show_loader('#wp-dp-dev-add-apartment-' + _this_id + '', '', 'button_loader', thisObj);

        var data = new FormData();
        data.append('plot_field', _plot_field.val());
        data.append('beds_field', _beds_field.val());
        data.append('price_from_field', _price_field.val());
        data.append('floor_field', _floor_field.val());
        data.append('address_field', _address_field.val());
        data.append('availability_field', _availability_field.val());
        data.append('link_field', _link_field.val());
        data.append('listing_add_counter', _this_id);
        data.append('action', 'wp_dp_listing_apartment_to_list');
        adding_service = $.ajax({
            url: ajax_url,
            method: "POST",
            data: data,
            cache: false,
            dataType: "json",
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        }).done(function (response) {
            if (typeof response.html !== 'undefined') {
                no_service_msg.remove();
                _this_append.append(response.html);
                _plot_field.val('');
                $('#wp-dp-dev-insert-apartment-con-' + _this_id).find('.icons-selector .selector .selected-icon > i').removeAttr('class');
                $('#wp-dp-dev-insert-apartment-con-' + _this_id).find('.icons-selector .selector .selected-icon > i').addClass('icon-cancel2');
                _beds_field.val('');
                _price_field.val('');
                _floor_field.val('');
                _address_field.val('');
                _availability_field.val('');
                _link_field.val('');
            }

            var response = {
                type: 'success',
                msg: wp_dp_listing_strings.apartment_added
            };
            wp_dp_show_response(response, '', thisObj);
            $('#wp-dp-dev-insert-apartment-con-' + _this_id).slideUp();
        }).fail(function () {
            wp_dp_show_response('', '', thisObj);
        });
    } else {
        var response = {
            type: 'error',
            msg: wp_dp_listing_strings.compulsory_fields
        };
        wp_dp_show_response(response);
    }
});

$(document).on('click', '.remove-this-apartment', function () {
    $(this).parent('.info-holder').slideUp();
});

$(document).on('click', 'a[id^="wp-dp-dev-apartment-edit-"]', function () {
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-apartment-edit-con-' + _this_id);
    _this_con.slideToggle();

});

$(document).on('click', 'a[id^="wp-dp-dev-insert-apartment-"]', function () {
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-insert-apartment-con-' + _this_id);
    _this_con.slideToggle();

});

$(document).on('click', 'a[id^="wp-dp-dev-apartment-save-"]', function () {
    "use strict";
    var _this_id = $(this).data('id'),
            _this_con = $('#wp-dp-dev-apartment-edit-con-' + _this_id),
            plot_val = _this_con.find('.apartment-plot').val();
    beds_val = _this_con.find('.apartment-beds').val();
    price_val = _this_con.find('.apartment-price-from').val();
    floor_val = _this_con.find('.apartment-floor').val();
    address_val = _this_con.find('.apartment-address').val();
    availibility_val = _this_con.find('.apartment-availability').val();
    link_val = _this_con.find('.apartment-link').val();

    var thisObj = jQuery('#wp-dp-dev-apartment-save-' + _this_id + '');
    wp_dp_show_loader('#wp-dp-dev-apartment-save-' + _this_id + '', '', 'button_loader', thisObj);

    $('#apartment-plot-' + _this_id).html('<h6>' + plot_val + '</h6>');
    $('#apartment-beds-' + _this_id).html('<h6>' + beds_val + '</h6>');
    $('#apartment-price-' + _this_id).html('<h6>' + price_val + '</h6>');
    $('#apartment-floor-' + _this_id).html('<h6>' + floor_val + '</h6>');
    $('#apartment-address-' + _this_id).html('<h6>' + address_val + '</h6>');
    $('#apartment-availability-' + _this_id).html('<h6>' + availibility_val + '</h6>');
    $('#apartment-title-' + _this_id).html('<h6>' + link_val + '</h6>');

    setTimeout(function () {
        wp_dp_show_response('', '', thisObj);
        _this_con.slideUp();
    }, 500);
});


/*
 * End Apartment
 */


jQuery(document).on("click", "#add-faq #wp_dp_add_faq_to_list", function () {
    "use strict";
    
    var thisObj = jQuery(this);
    var this_loader_Obj = jQuery(".faq-request-holder");
    wp_dp_show_loader(".faq-request-holder", "", "button_loader", this_loader_Obj);

    thisObj.prop('disabled', true);
    var faq_title = jQuery('#add-faq #wp_dp_faq_title').val();
    var faq_desc = jQuery('#add-faq #wp_dp_faq_desc').val();
    var dataString = 'faq_title='+ faq_title + '&faq_desc=' + faq_desc + '&action=add_faq_to_list';
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            thisObj.prop('disabled', false);
            wp_dp_show_response(response, "", this_loader_Obj);
            if (response.type == 'success') {
                jQuery('.listing-faq-list').append(response.html);
                jQuery('#add-faq .close-faq').click();
                jQuery('#add-faq #wp_dp_faq_title').val('');
                jQuery('#add-faq #wp_dp_faq_desc').val('');
            }
        }
    });
    return false;
});

jQuery(document).on("click", ".listing-faq-list .edit-faq", function () {
    "use strict";
    var thisObj = jQuery(this);
    var faq_title = thisObj.closest('li').find('#wp_dp_faq_title').val();
    var faq_desc = thisObj.closest('li').find('#wp_dp_faq_desc').val();
    var faq_counter = thisObj.closest('li').find('#wp_dp_faq_counter').val();
    jQuery('#edit-faq').modal('show');
    jQuery('#edit-faq').find('#wp_dp_faq_title').val(faq_title);
    jQuery('#edit-faq').find('#wp_dp_faq_desc').val(faq_desc);
    jQuery('#edit-faq').find('#wp_dp_faq_counter').val(faq_counter);
});

jQuery(document).on("click", "#wp_dp_edit_faq_to_list", function () {
    "use strict";
    
    var thisObj = jQuery(this);
    var this_loader_Obj = jQuery(".edit-faq-request-holder");
    wp_dp_show_loader(".edit-faq-request-holder", "", "button_loader", this_loader_Obj);
    
    thisObj.prop('disabled', true);
    var faq_title = jQuery('#edit-faq #wp_dp_faq_title').val();
    var faq_desc = jQuery('#edit-faq #wp_dp_faq_desc').val();
    var faq_counter = jQuery('#edit-faq #wp_dp_faq_counter').val();
    var dataString = 'faq_title='+ faq_title + '&faq_desc=' + faq_desc + '&faq_counter=' + faq_counter + '&action=edit_faq_to_list';
    
    jQuery.ajax({
        type: "POST",
        url: wp_dp_globals.ajax_url,
        data: dataString,
        dataType: 'json',
        success: function (response) {
            thisObj.prop('disabled', false);
            wp_dp_show_response(response, "", this_loader_Obj);
            if (response.type == 'success') {
                jQuery('.listing-faq-list .faq-'+ faq_counter).html();
                jQuery('.listing-faq-list .faq-'+ faq_counter).html(response.html);
                jQuery('#edit-faq').modal('hide');
                jQuery('#edit-faq #wp_dp_faq_title').val('');
                jQuery('#edit-faq #wp_dp_faq_desc').val('');
            }
        }
    });
    return false;
});

jQuery(document).on("click", ".listing-faq-list .remove-faq a", function() {
    "use strict";
    var thisObj = jQuery(this);
    jQuery("#id_confrmdiv").show();
    jQuery("#id_truebtn").click(function() {
        jQuery("#id_confrmdiv").hide();
        thisObj.closest('li').slideUp(500, function () {
            thisObj.closest('li').remove();
        });
        return false;
    });
    jQuery("#id_falsebtn").click(function() {
        jQuery("#id_confrmdiv").hide();
        return false;
    });
});

$(document).on("click", ".book-btn", function() {
    "use strict";
    $(this).next(".calendar-holder").slideToggle("fast");

});

$(document).on("click", 'a[id^="wp-dp-dev-day-off-dp-"]', function() {
    "use strict";
    var _this_id = $(this).data("id");
    $("#day-dpove-" + _this_id).remove();
});
var counter = 0;
$(document).on("click", ".wp-dp-dev-insert-off-days .wp-dp-dev-calendar-days .day a", function() {
    "use strict";
    if( counter == 0 ){
        counter = 1;
        var adding_off_day, _this_ = $(this),
            _this_id = $(this).parents(".wp-dp-dev-insert-off-days").data("id"),
            _day = $(this).data("day"),
            _month = $(this).data("month"),
            _year = $(this).data("year"),
            _adding_date = _year + "-" + _month + "-" + _day,
            _add_date = true,
            _this_append = $("#wp-dp-dev-add-off-day-app-" + _this_id),
            no_off_day_msg = _this_append.find("#no-book-day-" + _this_id),
            this_loader = $("#dev-off-day-loader-" + _this_id),
            this_act_msg = $("#wp-dp-dev-act-msg-" + _this_id);
        _this_append.find("li").each(function() {
            var date_field = $(this).find('input[name^="wp_dp_listing_off_days"]');
            if (_adding_date == date_field.val()) {
                var response = {
                    type: "success",
                    msg: wp_dp_listing_strings.off_day_already_added
                };
                wp_dp_show_response(response);
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
                    action: "wp_dp_listing_off_day_to_list"
                },
                dataType: "json"
            }).done(function(response) {
                if (typeof response.html !== "undefined") {
                    no_off_day_msg.remove();
                    _this_append.append(response.html);
                    this_act_msg.html(wp_dp_listing_strings.off_day_added);
                }
                var response = {
                    type: "success",
                    msg: wp_dp_listing_strings.off_day_added
                };
                wp_dp_show_response(response, "", thisObj);
                jQuery(".calendar-holder").slideToggle("fast");
                $("#wp-dp-dev-cal-holder-" + _this_id).slideUp("fast");
                counter = 0;
            }).fail(function() {
                wp_dp_show_response("", "", thisObj);
                counter = 0;
            });
        }
    }
});