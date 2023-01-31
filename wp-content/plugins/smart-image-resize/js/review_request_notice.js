/**
 * @global wp_sir_review_request_params
 */
( function ( $ ) {

    var WP_SIR_REVIEW_REQUEST_UTIL = {
        setCookie: function (cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
    };
    $(document).on('click', '#wp-sir-review-request-notice-remindme', function(){
        $( this ).closest( '.notice' ).remove();
        WP_SIR_REVIEW_REQUEST_UTIL.setCookie( 'wp_sir_review_request_notice_remindme', '1', { expires: 1 } );
        $.post( wp_sir_review_request_params.ajax_url, {
            action: 'wp_sir/remindme_review_request_notice',
            nonce: wp_sir_review_request_params.nonce
        } );
    });

    $(document).on('click', '#wp-sir-review-request-notice-dismiss', function(){
        $( this ).closest( '.notice' ).remove();
        WP_SIR_REVIEW_REQUEST_UTIL.setCookie( 'wp_sir_review_request_notice_dismissed', '1', { expires: 1 } );
        $.post( wp_sir_review_request_params.ajax_url, {
            action: 'wp_sir/dismiss_review_request_notice',
            nonce: wp_sir_review_request_params.nonce
        } );
    });

} )( jQuery );