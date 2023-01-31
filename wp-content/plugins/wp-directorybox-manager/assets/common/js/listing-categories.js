var listingCategoryFilterAjax;
function wp_dp_load_category_models(selected_val, post_id, main_container, load_saved_value, data_child, parent_loader) {
    'use strict';
    data_child = data_child = parent_loader || '';
    var data_vals = '';
    if (typeof (listingCategoryFilterAjax) != 'undefined') {
        listingCategoryFilterAjax.abort();
    }

    var this_loader = jQuery('.type-categry-holder-main-' + post_id);

    if (parent_loader == 'parent_loader') {
        this_loader.addClass('active-ajax');
    }

    var wp_dp_listing_category = jQuery("#wp_dp_listing_category").val();
    listingCategoryFilterAjax = jQuery.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: wp_dp_globals.ajax_url,
        data: data_vals + '&action=wp_dp_meta_listing_categories&selected_val=' + selected_val + '&data_child=' + data_child + '&post_id=' + post_id + '&wp_dp_listing_category=' + wp_dp_listing_category + '&load_saved_value=' + load_saved_value,
        success: function (response) {
            jQuery("." + main_container).html(response.html);
            jQuery(".chosen-select").chosen();

            if (parent_loader == 'parent_loader') {
                this_loader.removeClass('active-ajax');
            }
        }
    });
}