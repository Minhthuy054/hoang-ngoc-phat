jQuery(document).ready(function (jQuery) {
    jQuery.fn.extend({
        cityAutocomplete: function (options) {

            return this.each(function () {

                var input = jQuery(this), opts = jQuery.extend({}, jQuery.cityAutocomplete);
                var input_id = input.data('id');
                
                var autocompleteService = new google.maps.places.AutocompleteService();

                var predictionsDropDown = jQuery('<div class="wp_dp_location_autocomplete" class="city-autocomplete"></div>').appendTo(jQuery(this).parent());

               var currentLocationWrapper = jQuery('<div class="current-location-wrapper" style="display: none;"></div>');
                predictionsDropDown.append(currentLocationWrapper);

                var predictionsLoader = jQuery('<div class="location-loader-wrapper" style="display: none;"><i class="fancy-spinner"></i></div>');
                if( predictionsDropDown.closest('.wp-dp-locations-fields-group').find('label').length > 0 ){
                    predictionsDropDown.closest('.wp-dp-locations-fields-group').find('label').append(predictionsLoader);
                } else {
                    predictionsDropDown.closest('.wp-dp-locations-fields-group').append(predictionsLoader);
                }
                
                var cross_icon = jQuery('.wp-dp-input-cross'+ input_id);

                var currentLocationWrapper = jQuery('<div class="current-location-wrapper" style="display: none;"></div>');
                predictionsDropDown.append(currentLocationWrapper);

                var predictionsGoogleWrapper = jQuery('<div class="location-google-wrapper" style="display: none;"></div>');
                predictionsDropDown.append(predictionsGoogleWrapper);

                var predictionsDBWrapper = jQuery('<div class="location-db-wrapper" style="display: none;"></div>');
                predictionsDropDown.append(predictionsDBWrapper);

                var plugin_url = input.parent(".wp_dp_searchbox_div").data('locationadminurl');

                var last_query = '';
                var new_query = '';
                var xhr = '';
                input.click(function () {
                    cross_icon.hide();
                    predictionsLoader.show();
                    predictionsGoogleWrapper.hide();
                    currentLocationWrapper.hide();
                    predictionsDBWrapper.hide(); 
                    
                    input.attr('placeholder', 'destination, city, address');
                    updateGooglePredictions();
                    predictionsDropDown.show();
                });
//                blur(function () {
//                    input.attr('placeholder', 'Location');
//                });
                input.keyup(function () {
                    new_query = input.val();
                    // Min Number of characters
                    var num_of_chars = 0;
                    if (new_query.length > num_of_chars) {
                        predictionsDropDown.show();
                        predictionsGoogleWrapper.hide();
                        currentLocationWrapper.hide();
                        predictionsDBWrapper.hide();
                        predictionsLoader.show();

                        if (input.hasClass('wp-dp-locations-field-geo' + input_id)) {
                            var params = {
                                input: new_query,
                                bouns: 'upperbound',
                                //types: ['address'],
                                componentRestrictions: '', //{country: window.country_code}
                            };
                            //params.componentRestrictions = ''; //{country: window.country_code}
                            autocompleteService.getPlacePredictions(params, updateGooglePredictions);
                        }
                        updateDBPredictions();
                    } else {
                        predictionsDropDown.hide();
                    }
                    $("input.search_type").val('custom');
                });

                function updateGooglePredictions(predictions, status) {
                    var input_id = input.data('id');
                    var google_results = '';
                    currentLocationWrapper.show();
                    // var dataString = 'action=get_current_locations_for_search';
                    var dataString = 'action=current_location_for_field&input_id=' + input_id;

                    jQuery.ajax({
                        type: "POST",
                        url: wp_dp_globals.ajax_url,
                        data: dataString,
                        success: function (data) {
                            var results = jQuery.parseJSON(data);
                            if (results.current_location !== '') {
                                currentLocationWrapper.empty();
                                currentLocationWrapper.append(results.current_location).show();
                            } else {
                                currentLocationWrapper.hide();
                            }

                            if (google.maps.places.PlacesServiceStatus.OK == status) {
                                // AJAX GET ADDRESS FROM GOOGLE
                                google_results += '<div class="address_headers"><strong>Address</strong></div>'
                                jQuery.each(predictions, function (i, prediction) {
                                    google_results += '<div class="wp_dp_google_suggestions"><i class="icon-location-arrow"></i>' + jQuery.fn.cityAutocomplete.transliterate(prediction.description) + '<span style="display:none">' + jQuery.fn.cityAutocomplete.transliterate(prediction.description) + '</span></div>';
                                });
                                predictionsLoader.hide();
                                if( input.val() != '' ){
                                    cross_icon.show();
                                }
                                predictionsGoogleWrapper.empty().append(google_results).show();
                            } else {
                                predictionsLoader.hide();
                                if( input.val() != '' ){
                                    cross_icon.show();
                                }
                            }
                        }
                    });


                }

                function updateDBPredictions() {
                    if (last_query == new_query) {
                        return;
                    }
                    last_query = new_query;
                    // AJAX GET STATE / PROVINCE.
                    var dataString = 'action=get_locations_for_search' + '&keyword=' + new_query;
                    if (xhr != '') {
                        xhr.abort();
                    }
                    xhr = jQuery.ajax({
                        type: "POST",
                        url: wp_dp_globals.ajax_url,
                        data: dataString,
                        success: function (data) {
                            var results = jQuery.parseJSON(data);
                            if (results.current_location !== '') {
                                currentLocationWrapper.empty();
                                currentLocationWrapper.append(results.current_location).show();
                            }
                            if (results != '') {
                                // Set label for suggestions.
                                var labels_str = "";
                                if (typeof results.title != "undefined") {
                                    labels_str = results.title.join(" / ");
                                }
                                var locations_str = "";
                                // Populate suggestions.
                                if (typeof results.locations_for_display != "undefined") {
                                    var data = results.locations_for_display;
                                    $.each(data, function (key1, val1) {
                                        if (results.location_levels_to_show[0] == true && typeof val1.item != "undefined") {
                                            locations_str += '<div class="wp_dp_google_suggestions wp_dp_location_parent"><i class="icon-location-arrow"></i>' + val1.item.name + '<span style="display:none">' + val1.item.slug + '</span></div>';
                                        }
                                        if (val1.children.length > 0) {
                                            $.each(val1.children, function (key2, val2) {
                                                if (results.location_levels_to_show[1] == true && typeof val2.item != "undefined") {
                                                    locations_str += '<div class="wp_dp_google_suggestions wp_dp_location_child"><i class="icon-location-arrow"></i>' + val2.item.name + '<span style="display:none">' + val2.item.slug + '</span></div>';
                                                }
                                                if (val2.children.length > 0) {
                                                    $.each(val2.children, function (key3, val3) {
                                                        if (results.location_levels_to_show[2] == true && typeof val3.item != "undefined") {
                                                            locations_str += '<div class="wp_dp_google_suggestions wp_dp_location_child"><i class="icon-location-arrow"></i>' + val3.item.name + '<span style="display:none">' + val3.item.slug + '</span></div>';
                                                        }
                                                        if (val3.children.length > 0) {
                                                            $.each(val3.children, function (key4, val4) {
                                                                if (results.location_levels_to_show[3] == true && typeof val4.item != "undefined") {
                                                                    locations_str += '<div class="wp_dp_google_suggestions wp_dp_location_child"><i class="icon-location-arrow"></i>' + val4.item.name + '<span style="display:none">' + val4.item.slug + '</span></div>';
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    });
                                    predictionsDBWrapper.empty();
                                    if (locations_str != "") {
                                        predictionsLoader.hide();
                                        if( input.val() != '' ){
                                            cross_icon.show();
                                        }
                                        predictionsDBWrapper.append('<div class="address_headers"><strong>' + labels_str + '</strong></div>' + locations_str).show();
                                    } else {
                                        predictionsLoader.hide();
                                        if( input.val() != '' ){
                                            cross_icon.show();
                                        }
                                    }
                                }
                            }

                        }
                    });
                }

                predictionsDropDown.delegate('div.wp_dp_google_suggestions', 'click', function () {
                    
                    if (jQuery(this).text() != "ADDRESS" && jQuery(this).text() != "STATE / PROVINCE" && jQuery(this).text() != "COUNTRY") {
                        // address with slug			
                      //  var wp_dp_address_html = jQuery(this).text();
                        // slug only
                        var wp_dp_address_slug = jQuery(this).find('span').html();
                        cross_icon.show();
                        // remove slug
                        jQuery(this).find('span').remove();
                        input.val(jQuery(this).text());
                        input.next('.search_keyword').val(wp_dp_address_slug);
                        jQuery("input.search_type").val('autocomplete');
                        input.next('.search_type').val('autocomplete');
                        predictionsDropDown.hide();
                        if( input.parents().find( 'form[name="wp-dp-top-map-form"]' ).length > 0 ) {
                            var id = input.parents().find( 'form[name="wp-dp-top-map-form"]' ).data('id');
                            wp_dp_top_serach_trigger( id );
                        }
                        input.next('.search_keyword').closest("form.side-loc-srch-form").submit();
                        
                    }
                });
                
                

                jQuery(document).mouseup(function (e) {
                    input.attr('placeholder', 'Location');
                    predictionsDropDown.hide();
                });

                jQuery(window).resize(function () {
                    updatePredictionsDropDownDisplay(predictionsDropDown, input);
                });
                updatePredictionsDropDownDisplay(predictionsDropDown, input);
                return input;
            });
        }
    });
    jQuery.fn.cityAutocomplete.transliterate = function (s) {
        s = String(s);
        var char_map = {
            // Latin
            'À': 'A', '�?': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE', 'Ç': 'C',
            'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ì': 'I', '�?': 'I', 'Î': 'I', '�?': 'I',
                    '�?': 'D', 'Ñ': 'N', 'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', '�?': 'O',
            'Ø': 'O', 'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ű': 'U', '�?': 'Y', 'Þ': 'TH',
            'ß': 'ss',
            'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae', 'ç': 'c',
            'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i',
            'ð': 'd', 'ñ': 'n', 'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ő': 'o',
            'ø': 'o', 'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ű': 'u', 'ý': 'y', 'þ': 'th',
            'ÿ': 'y',
            // Latin symbols
            '©': '(c)',
            // Greek
            'Α': 'A', 'Β': 'B', 'Γ': 'G', 'Δ': 'D', 'Ε': 'E', 'Ζ': 'Z', 'Η': 'H', 'Θ': '8',
            'Ι': 'I', 'Κ': 'K', 'Λ': 'L', 'Μ': 'M', '�?': 'N', 'Ξ': '3', 'Ο': 'O', 'Π': 'P',
            'Ρ': 'R', 'Σ': 'S', 'Τ': 'T', 'Υ': 'Y', 'Φ': 'F', 'Χ': 'X', 'Ψ': 'PS', 'Ω': 'W',
            'Ά': 'A', 'Έ': 'E', 'Ί': 'I', 'Ό': 'O', 'Ύ': 'Y', 'Ή': 'H', '�?': 'W', 'Ϊ': 'I',
            'Ϋ': 'Y',
            'α': 'a', 'β': 'b', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'h', 'θ': '8',
            'ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': '3', 'ο': 'o', 'π': 'p',
            '�?': 'r', 'σ': 's', 'τ': 't', 'υ': 'y', 'φ': 'f', 'χ': 'x', 'ψ': 'ps', 'ω': 'w',
            'ά': 'a', 'έ': 'e', 'ί': 'i', 'ό': 'o', '�?': 'y', 'ή': 'h', 'ώ': 'w', 'ς': 's',
            'ϊ': 'i', 'ΰ': 'y', 'ϋ': 'y', '�?': 'i',
                    // Turkish
                    'Ş': 'S', 'İ': 'I', 'Ç': 'C', 'Ü': 'U', 'Ö': 'O', 'Ğ': 'G',
            'ş': 's', 'ı': 'i', 'ç': 'c', 'ü': 'u', 'ö': 'o', 'ğ': 'g',
            // Russian
            '�?': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', '�?': 'Yo', 'Ж': 'Zh',
            'З': 'Z', 'И': 'I', 'Й': 'J', 'К': 'K', 'Л': 'L', 'М': 'M', '�?': 'N', 'О': 'O',
            'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C',
            'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Sh', 'Ъ': '', 'Ы': 'Y', 'Ь': '', 'Э': 'E', 'Ю': 'Yu',
            'Я': 'Ya',
            'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh',
            'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
            'п': 'p', 'р': 'r', '�?': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
            'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': '', 'ы': 'y', 'ь': '', '�?': 'e', 'ю': 'yu',
            '�?': 'ya',
                    // Ukrainian
                    'Є'
                    : 'Ye', 'І': 'I', 'Ї': 'Yi', '�?': 'G',
            'є'
                    : 'ye', 'і': 'i', 'ї': 'yi', 'ґ': 'g',
            // Czech
            'Č'
                    : 'C', 'Ď': 'D', 'Ě': 'E', 'Ň': 'N', 'Ř': 'R', 'Š': 'S', 'Ť': 'T', 'Ů': 'U',
            'Ž'
                    : 'Z',
            '�?'
                    : 'c', '�?': 'd', 'ě': 'e', 'ň': 'n', 'ř': 'r', 'š': 's', 'ť': 't', 'ů': 'u',
            'ž'
                    : 'z',
            // Polish
            'Ą'
                    : 'A', 'Ć': 'C', 'Ę': 'e', '�?': 'L', 'Ń': 'N', 'Ó': 'o', 'Ś': 'S', 'Ź': 'Z',
            'Ż'
                    : 'Z',
            'ą'
                    : 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z',
            'ż'
                    : 'z',
            // Latvian
            'Ā'
                    : 'A', 'Č': 'C', 'Ē': 'E', 'Ģ': 'G', 'Ī': 'i', 'Ķ': 'k', 'Ļ': 'L', 'Ņ': 'N',
            'Š'
                    : 'S', 'Ū': 'u', 'Ž': 'Z',
                    '�?'
                    : 'a', '�?': 'c', 'ē': 'e', 'ģ': 'g', 'ī': 'i', 'ķ': 'k', 'ļ': 'l', 'ņ': 'n',
            'š'
                    : 's', 'ū': 'u', 'ž': 'z'
        };
        for (var k in char_map) {
            //s = s.replace(new RegExp(k, 'g'), char_map[k]);
        }
        return s;
    };
    function updatePredictionsDropDownDisplay(dropDown, input) {
        if (typeof (input.offset()) !== 'undefined') {
            dropDown.css({
                'width': input.outerWidth(),
                'left': input.offset().left,
                'top': input.offset().top + input.outerHeight()
            });
        }
    }

    jQuery('input.wp_dp_search_location_field').cityAutocomplete();

    jQuery(document).on('click', '.wp_dp_searchbox_div', function () {
        jQuery('.wp_dp_search_location_field').prop('disabled', false);
    });

    jQuery(document).on('click', 'form', function () {
        var src_loc_val = jQuery(this).find('.wp_dp_search_location_field');
        src_loc_val.next('.search_keyword').val(src_loc_val.val());
    });
//    jQuery(document).on('click', '.wp-dp-location-field', function () {
//        var $this = jQuery(this);
//       var input_id = $this.data('id');
//        $this.closest('.wp_dp_searchbox_div').find('.wp_dp_location_autocomplete').show();
//        var dataString = 'action=current_location_for_field&input_id=' + input_id; ;
//        jQuery.ajax({
//            type: "POST",
//            url: wp_dp_globals.ajax_url,
//            data: dataString,
//            success: function (data) {  
//                $this.closest('.wp_dp_searchbox_div').find('.wp_dp_location_autocomplete').html(data);
//               // jQuery(this).closest('.wp_dp_searchbox_div').find('.wp_dp_location_autocomplete').show();
//
//            }
//        });
//       
//    });
    //if (jQuery(".main-header .field-holder.search-input.with-search-country .search-country input").length != "") {
    // $('.main-header .field-holder.search-input.with-search-country .search-country input').focus(function() {
    //  $(this).attr('placeholder', 'destination, city, address')
    // }).blur(function() {
    //  $(this).attr('placeholder', 'Location')
    // });
    //}
}(jQuery));