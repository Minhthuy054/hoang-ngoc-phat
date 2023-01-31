function wp_dp_date_range_filter(field_name, actionString, date_picker_position) {
    "use strict";
    if (typeof date_picker_position === "undefined" || date_picker_position === '') {
        date_picker_position = 'left';
    }
    jQuery('#' + field_name).daterangepicker({
        autoUpdateInput: false,
        opens: date_picker_position,
        locale: {
            format: 'DD/MM/YYYY'
        }
    },
            function (start, end) {
                var date_range = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
                jQuery('#' + field_name).val(date_range);
                var pageNum = 1;
                wp_dp_show_loader('.loader-holder');
                var filter_parameters = wp_dp_get_filter_parameters();
                if (typeof (ajaxRequest) != 'undefined') {
                    ajaxRequest.abort();
                }
                ajaxRequest = jQuery.ajax({
                    type: "POST",
                    url: wp_dp_globals.ajax_url,
                    data: 'page_id_all=' + pageNum + '&action=' + actionString + filter_parameters,
                    success: function (response) {
                        wp_dp_hide_loader();
                        jQuery('.user-holder').html(response);

                    }
                });
            });
    jQuery('#' + field_name).on('cancel.daterangepicker', function (ev, picker) {
        "use strict";
        jQuery('#' + field_name).val('');
        var pageNum = 1;
        wp_dp_show_loader('.loader-holder');
        var filter_parameters = wp_dp_get_filter_parameters();
        if (typeof (ajaxRequest) != 'undefined') {
            ajaxRequest.abort();
        }
        ajaxRequest = jQuery.ajax({
            type: "POST",
            url: wp_dp_globals.ajax_url,
            data: 'page_id_all=' + pageNum + '&action=' + actionString + filter_parameters,
            success: function (response) {
                wp_dp_hide_loader();
                jQuery('.user-holder').html(response);

            }
        });
    });
}

function wp_dp_get_filter_parameters($this) {

    "use strict";

    $this = $this || '';
    var date_range = jQuery(".user-holder").find("#date_range").val();

    var filter_var = "";
    if (typeof date_range != "undefined" && date_range !== "") {
        filter_var += "&date_range=" + date_range;
    }

    var data_param = jQuery($this).attr("data-param");
    if (typeof data_param !== "undefined" && data_param != '' && data_param != null) {
        filter_var += "&data_param=" + data_param;
    }
    
    var data_sort = jQuery($this).attr("data-sort");
    if (typeof data_sort !== "undefined" && data_sort != '' && data_sort != null) {
        filter_var += "&data_sort=" + data_sort;
    }
    var data_type = jQuery($this).attr("data-type");
    if (typeof data_type !== "undefined" && data_type != '' && data_type != null) {
        filter_var += "&data_type=" + data_type;
    }

    return filter_var;

}