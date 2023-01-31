(function($){
    $(document).ready(function(){
        var $defaultSetting = {
            formatNoMatches: woocommerce_district_admin.formatNoMatches,
        };
        var loading_billing = loading_shipping = false;
        //billing
        $('#_billing_state').select2($defaultSetting);
        $('#_billing_city').select2($defaultSetting);
        $('#_billing_address_2').select2($defaultSetting);

        $('body').on('select2:select select2-selecting', '#_billing_state',function(e){
            $( "#_billing_city option" ).val('');
            var matp = e.val;
            if(!matp) matp = $( "#_billing_state option:selected" ).val();
            if(matp && !loading_billing){
                loading_billing = true;
                $.ajax({
                    type : "post",
                    dataType : "json",
                    url : woocommerce_district_admin.ajaxurl,
                    data : {action: "load_diagioihanhchinh", matp : matp},
                    context: this,
                    beforeSend: function(){
                        $("#_billing_city,#_billing_address_2").html('').select2();
                        var newState = new Option('Loading...', '');
                        $("#_billing_city, #_billing_address_2").append(newState);
                    },
                    success: function(response) {
                        loading_billing = false;
                        $("#_billing_city,#_billing_address_2").html('').select2();
                        var newState = new Option('Chọn xã/phường/thị trấn', '');
                        $("#_billing_address_2").append(newState);
                        if(response.success) {
                            var listQH = response.data;
                            newState = new Option('Chọn quận/huyện', '');
                            $("#_billing_city").append(newState);
                            $.each(listQH,function(index,value){
                                newState = new Option(value.name, value.maqh);
                                $("#_billing_city").append(newState);
                            });
                        }
                    }
                });
            }
        });
        if($('#_billing_address_2').length > 0){
            $('#_billing_city').on('change select2:select select2-selecting',function(e){
                var maqh = e.val;
                if(!maqh) maqh = $( "#_billing_city option:selected" ).val();
                if(maqh) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: woocommerce_district_admin.ajaxurl,
                        data: {action: "load_diagioihanhchinh", maqh: maqh},
                        context: this,
                        beforeSend: function(){
                            $("#_billing_address_2").html('').select2();
                            var newState = new Option('Loading...', '');
                            $("#_billing_address_2").append(newState);
                        },
                        success: function (response) {
                            $("#_billing_address_2").html('').select2($defaultSetting);
                            if (response.success) {
                                var listQH = response.data;
                                var newState = new Option('Chọn xã/phường/thị trấn', '');
                                $("#_billing_address_2").append(newState);
                                $.each(listQH, function (index, value) {
                                    var newState = new Option(value.name, value.xaid);
                                    $("#_billing_address_2").append(newState);
                                });
                            }
                        }
                    });
                }
            });
        }
        //shipping
        $('#_shipping_state').select2($defaultSetting);
        $('#_shipping_city').select2($defaultSetting);
        $('#_shipping_address_2').select2($defaultSetting);

        $('body #_shipping_state').on('select2:select select2-selecting',function(e){
            $( "#_shipping_city option" ).val('');
            var matp = e.val;
            if(!matp) matp = $( "#_shipping_state option:selected" ).val();
            if(matp && !loading_shipping){
                loading_shipping = true;
                $.ajax({
                    type : "post",
                    dataType : "json",
                    url : woocommerce_district_admin.ajaxurl,
                    data : {action: "load_diagioihanhchinh", matp : matp},
                    context: this,
                    beforeSend: function(){
                        $("#_shipping_city,#_shipping_address_2").html('').select2();
                        var newState = new Option('Loading...', '');
                        $("#_shipping_city, #_shipping_address_2").append(newState);
                    },
                    success: function(response) {
                        loading_shipping = false;
                        $("#_shipping_city,#_shipping_address_2").html('').select2();
                        var newState = new Option('Chọn xã/phường/thị trấn', '');
                        $("#_shipping_address_2").append(newState);
                        if(response.success) {
                            var listQH = response.data;
                            var newState = new Option('Chọn quận/huyện', '');
                            $("#_shipping_city").append(newState);
                            $.each(listQH,function(index,value){
                                var newState = new Option(value.name, value.maqh);
                                $("#_shipping_city").append(newState);
                            });
                        }
                    }
                });
            }
        });
        if($('#_shipping_address_2').length > 0){
            $('#_shipping_city').on('change select2:select select2-selecting',function(e){
                var maqh = e.val;
                if(!maqh) maqh = $( "#_shipping_city option:selected" ).val();
                if(maqh) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: woocommerce_district_admin.ajaxurl,
                        data: {action: "load_diagioihanhchinh", maqh: maqh},
                        context: this,
                        beforeSend: function(){
                            $("#_shipping_address_2").html('').select2();
                            var newState = new Option('Loading...', '');
                            $("#_shipping_address_2").append(newState);
                        },
                        success: function (response) {
                            $("#_shipping_address_2").html('').select2($defaultSetting);
                            if (response.success) {
                                var listQH = response.data;
                                var newState = new Option('Chọn xã/phường/thị trấn', '');
                                $("#_shipping_address_2").append(newState);
                                $.each(listQH, function (index, value) {
                                    var newState = new Option(value.name, value.xaid);
                                    $("#_shipping_address_2").append(newState);
                                });
                            }
                        }
                    });
                }
            });
        }
    });
})(jQuery);