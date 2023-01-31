jQuery('body').append('<div id="print-area-appender" style="display:none;"></div>');
jQuery(window).load(function () {
    var apendPrint = jQuery('#print-area-appender');
    var data = "listing_id=" + wp_dp_print_str.listing_id + "&action=wp_dp_listing_detail_print_data";
    var print_data_append = $.ajax({
        url: wp_dp_print_str.ajax_url,
        method: "POST",
        data: data,
    }).done(function (response) {
        //console.log(response);
        apendPrint.html(response);
    });
});
function wp_dp_listing_detail_print( bootstrap_css_url, print_css_url) {
    
    var content = document.getElementById("print-area-appender").innerHTML;
    var winWidth = jQuery(window).width();
    var winHeight = jQuery(window).height();
    var win = window.open("", "_blank", "width=" + winWidth + ",height=" + winHeight + "");
    var finContent = '\
    <!DOCTYPE html>\
    <html>\
        <head>\
            <title>Print Page</title>\
            <link href="'+ wp_dp_print_str.bootstrap_css +'" rel="stylesheet" type="text/css">\
            <link href="'+ wp_dp_print_str.print_css +'" rel="stylesheet" type="text/css">\
            '+ wp_dp_print_str.iconmoon_css +'\
            <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>\
            <script>$(window).load(function(){ print(); });</script>\
        </head>\
        <body>'
            + content +
            '</body>\
    <html>';
    win.document.write(finContent);
    win.document.close();
    return false;
}