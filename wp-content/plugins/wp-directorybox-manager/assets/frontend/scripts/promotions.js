/**  jquery document.ready functions */
var $ = jQuery;
var ajaxRequest;


jQuery(document).on('change', '.promotion-selection', function () {
    var is_checked = jQuery(this).is(":checked");
    var vat = jQuery(this).closest('.promotion-popup-area').data('vat');
    var price = jQuery(this).data('price');
    var total_price = jQuery(".promotion-total strong").html();
    var vat_value = jQuery(".promotion-total strong").attr('data-vat');
    if( total_price != undefined && total_price > 0 && vat > 0){
        total_price = parseInt(total_price) - parseInt(vat_value);
        jQuery(".promotion-total strong").attr('data-vat',parseInt(vat_value));
    }
    if (is_checked == true) {
        total_price = parseInt(total_price) + parseInt(price);
    } else {
        total_price = parseInt(total_price) - parseInt(price);
    }
    if( total_price > 0 && vat > 0){
        var vat_value = parseInt(total_price) * ( parseInt(vat) / 100 );
        total_price += parseInt(vat_value);
        jQuery(".promotion-total strong").attr('data-vat',parseInt(vat_value));
    }
    jQuery(".promotion-total strong").html(total_price)
});


jQuery(document).on('click', '.promotions-pay', function () {
    var data_id = jQuery(this).data('id');
    var is_checked = jQuery('.promotion-selection').is(":checked");
    if (is_checked == false) {
        alert(wp_dp_globals.promotion_error);
    } else {
        var form_data = new FormData(jQuery('#promotions-form-' + data_id)[0]);
        form_data.append('listing_id', data_id);
        form_data.append('action', 'wp_dp_promotions_pay');
        jQuery.ajax({
            type: "POST",
            contentType: false,
            processData: false,
            url: wp_dp_globals.ajax_url,
            data: form_data,
            success: function (response) {
                jQuery('.promotion-body-' + data_id).html(response);
            }
        });
    }
});

/*
 * Process Payment
 */

jQuery(document).on('click', '.promotions-payment-process', function () {
    var data_id = jQuery(this).data('id');
    var form_data = new FormData(jQuery('#promotions-payment-form-' + data_id)[0]);
    form_data.append('listing_id', data_id);
    form_data.append('action', 'wp_dp_promotions_process');
    jQuery.ajax({
        type: "POST",
        contentType: false,
        processData: false,
        url: wp_dp_globals.ajax_url,
        data: form_data,
        success: function (response) {
            jQuery('.promotion-body-' + data_id).html(response);
        }
    });
});