var ajaxListingAutocomplete;

//jQuery(document).ready(function () {
jQuery(document).on("keyup", ".listing_autocomplete_on", function (e) {
    //jQuery(".listing_autocomplete_on").keyup(function () { 
    var input = jQuery(this);
    var input_id = input.data('id');
    var position = input.data('position');
    //input.closest('.search-listing-field').find('.listing_autocomplete_on_loader').html('<span class="fancy-spinner"></span>');
    //jQuery('.listing_autocomplete_data_dev').html('');
    jQuery('#listing-autocomplete-loader-' + input_id).html('<span class="fancy-spinner"></span>');
    jQuery('#listing-autocomplete-result-' + input_id).html('');
    var data_vals = 'autocomplete_field_txt=' + input.val();
    data_vals += '&counter=' + input_id;
    data_vals += '&position=' + position;
    if (typeof (ajaxListingAutocomplete) != 'undefined') {
        ajaxListingAutocomplete.abort();
    }
    ajaxListingAutocomplete = jQuery.ajax({
        type: 'POST',
        dataType: 'HTML',
        url: wp_dp_globals.ajax_url,
        data: data_vals + '&action=wp_dp_listing_autocomplete_data',
        success: function (response) {
//                debugger
            jQuery('#listing-autocomplete-loader-' + input_id).html('');
            jQuery('#listing-autocomplete-result-' + input_id).html(response);
            //input.closest('.search-listing-field').find('.listing_autocomplete_on_loader').html('');
            //jQuery('.listing_autocomplete_data_dev').html(response);
        }
    });
});

//});
jQuery(document).on("click", ".listing_autocomplete_on", function (e) {
    var input = jQuery(this);
    var input_id = input.data('id');
    input.attr('placeholder', 'homes, hotels and restaurants');
    jQuery('#listing-autocomplete-result-' + input_id).show();
    //jQuery('.listing_autocomplete_data_dev').show();
});
// close autocomplete div 
jQuery('body').click(function () {
    jQuery(".listing_autocomplete_data_dev").hide();
    //jQuery('input[name="search_title"]').attr('placeholder', 'Anywhere');
});
jQuery('.listings').on('click', 'li', function (e) {
    var listingtitle = jQuery('a',this).data('listingtitle');
    var counter = jQuery('a', this).data('id');
    var position = jQuery('a', this).data('position');
    var listingurl = jQuery('a', this).data('listingurl');
    jQuery('#listing-autocomplete-result-' + counter).html('');
    jQuery('#search-listing-field-' + counter + ' input[name="search_title"]').val(listingtitle);
    setTimeout(function () {
        jQuery(".cs_alerts").removeClass("active");
    }, 4e3);
    if (position === 'sidebar') {
        wp_dp_listing_content(counter);
    } else {
        window.location = listingurl;
    }
});

jQuery(document).on("click", ".listing-autocomplete-result ul li", function (e) {
    var listingurl = jQuery(this).find('a').data('listingurl');
    window.location.href = listingurl;
});


