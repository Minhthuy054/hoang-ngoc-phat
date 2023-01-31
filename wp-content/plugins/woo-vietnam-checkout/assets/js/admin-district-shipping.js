/*global $, woocommerce_district_shipping_rate_rows, ajaxurl */
( function( $, data, wp, ajaxurl ) {

	var wc_district_rate_box_rows_row_template = wp.template( 'district-rate-box-row-template' ),
		$boxes_table                    = $( '#flat_rate_boxes' ),
		$boxes                          = $boxes_table.find( 'tbody.flat_rate_boxes' );

	var wc_district_rate_box_rows = {
		init: function() {
			$boxes_table
				.on( 'click', 'a.add-box', this.onAddRate )
				.on( 'click', 'a.remove', this.onRemoveRate )

			var boxes_data = $boxes.data( 'boxes' );

			$( boxes_data ).each( function( i ) {
				var size = $boxes.find( '.flat_rate_box' ).length;
				$boxes.append( wc_district_rate_box_rows_row_template( {
					box:  boxes_data[ i ],
					index: size
				} ) );
			} );

			$boxes.sortable( {
				items: 'tr',
				cursor: 'move',
				axis: 'y',
				handle: 'td.sort_dwas_td',
				scrollSensitivity: 40,
				helper: function(e,ui){
					ui.children().each( function() {
						$( this ).width( $(this).width() );
					});
					ui.css( 'left', '0' );
					return ui;
				},
				start: function( event, ui ) {
					ui.item.css('background-color','#f6f6f6');
				},
				stop: function( event, ui ) {
					ui.item.removeAttr( 'style' );
					wc_district_rate_box_rows.reindexRows();
				}
			} );
		},
		onAddRate: function( event ) {
			event.preventDefault();
			var target = $boxes;
			var size   = target.find( '.flat_rate_box' ).length;

			target.append( wc_district_rate_box_rows_row_template( {
				box:  {
					box_id: '',
					box_district: '',
					box_cost: '',
					box_title: '',				},
				index: size
			} ) );
			$('.chosen_select').select2();
        },
		onRemoveRate: function( event ) {
			event.preventDefault();
			if ( confirm( data.i18n.delete_rates ) ) {
				var box_ids  = [];

				$boxes.find( 'tr td.check-column input:checked' ).each( function( i, el ) {
					var box_id = $(el).closest( 'tr.flat_rate_box' ).find( '.box_id' ).val();
					box_ids.push( box_id );
					$(el).closest( 'tr.flat_rate_box' ).addClass( 'deleting' );
				});

				var ajax_data = {
					action: 'woocommerce_district_rate_box_delete',
					box_id: box_ids,
					security: data.delete_box_nonce
				};

				$.post( ajaxurl, ajax_data, function(response) {
					$( 'tr.deleting').fadeOut( '300', function() {
						$( this ).remove();
					} );
				});
			}
		},
		reindexRows: function() {
			var loop = 0;
			$boxes.find( 'tr' ).each( function( index, row ) {
				$('input.text, input.checkbox, select.select', row ).each( function( i, el ) {
					var t = $(el);
					t.attr( 'name', t.attr('name').replace(/\[([^[]*)\]/, "[" + loop + "]" ) );
				});
                $('input.input_district_condition', row ).each( function( i, el ) {
                    var t = $(el);
                    t.attr( 'name', t.attr('name').replace(/\[([^[]*)\]/, "[" + loop + "]" ) );
                });
				loop++;
			});
		}
	};

	wc_district_rate_box_rows.init();

	$(document).ready(function () {
        $('body').on('change','.shipping_advance', function () {
            var thisParent = $(this).parents('.district_shipping_advance');
            var tableThis = thisParent.find('.dwas_price_list');
            if(tableThis.hasClass('dwas_hidden') && $(this).is(":checked")){
                tableThis.removeClass('dwas_hidden').addClass('dwas_show');
            }else{
                tableThis.addClass('dwas_hidden').removeClass('dwas_show');
            }
        });
        $('body').on('change', '.shipping_advance_w', function () {
            var thisParent = $(this).parents('.district_shipping_advance_weight');
            var tableThis = thisParent.find('.dwas_price_list');
            if(tableThis.hasClass('dwas_hidden') && $(this).is(":checked")){
                tableThis.removeClass('dwas_hidden').addClass('dwas_show');
            }else{
                tableThis.addClass('dwas_hidden').removeClass('dwas_show');
            }
        });
        $('body').on('click', '.dwas_add_condition', function () {
            var thisParent = $(this).parents('.district_shipping_advance');
            var cloneThis = thisParent.find('.dwas_price_list_tr').eq(1).clone();
            cloneThis.find('input').val('').attr('value','');
            $('.dwas_price_list_box',thisParent).append(cloneThis);

            var loop = -1;
            $('.dwas_price_list_box .dwas_price_list_tr',thisParent).each(function(index, row){
                $('input.input_district_condition', row ).each( function( i, el ) {
                    var t = $(el);
                    t.attr( 'name', t.attr('name').replace(/\[dk_([^[]*)\]/, "[dk_" + loop + "]" ) );
                });
                loop++;
            });

            return false;
        });
        $('body').on('click', '.dwas_delete_condition', function () {
            var thisParent = $(this).parents('.dwas_price_list_tr');
            var thisBox = $(this).parents('.dwas_price_list_box');
            if($('.dwas_price_list_tr',thisBox).length > 2){
                thisParent.fadeOut(400,function () {
                    $(this).remove();
                    var loop = -1;
                    $('.dwas_price_list_tr',thisBox).each(function(index, row){
                        $('input.input_district_condition', row ).each( function( i, el ) {
                            var t = $(el);
                            t.attr( 'name', t.attr('name').replace(/\[dk_([^[]*)\]/, "[dk_" + loop + "]" ) );
                        });
                        loop++;
                    });
                });
            }
            return false;
        });
        //all condition
		if($('#woocommerce_devvn_district_zone_shipping_all_price_condition').length > 0){
            $('#woocommerce_devvn_district_zone_shipping_all_price_condition').closest('tr').css("display", "none");
			$('body').on('click','.dwas_save_condition, .all_condition_district > label',function(){
                var loading = false;
                var conditionVal = $('#mainform').serialize();
                if(!loading) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajaxurl,
                        data: {action: "woocommerce_district_rate_array_to_serialize", data_form: conditionVal},
                        context: this,
                        beforeSend: function () {
                            loading = true;
                            $('.district_shipping_advance.all_condition_district').addClass('loading');

                        },
                        success: function (response) {
                            if(response.success){
                                $('#woocommerce_devvn_district_zone_shipping_all_price_condition').val(response.data);
                            }
                            $('.district_shipping_advance.all_condition_district').removeClass('loading');
                            loading = false;
                        }
                    })
                }
            });
    	}
        if($('#woocommerce_devvn_district_zone_shipping_all_price_condition_w').length > 0) {
            $('#woocommerce_devvn_district_zone_shipping_all_price_condition_w').closest('tr').css("display", "none");
            $('body').on('click', '.dwas_save_condition_w, .all_condition_district_w > label', function () {
                var loading = false;
                var conditionVal = $('#mainform').serialize();
                if (!loading) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajaxurl,
                        data: {action: "woocommerce_district_rate_array_to_serialize_weight", data_form: conditionVal},
                        context: this,
                        beforeSend: function () {
                            loading = true;
                            $('.district_shipping_advance.all_condition_district_w').addClass('loading');

                        },
                        success: function (response) {
                            if (response.success) {
                                $('#woocommerce_devvn_district_zone_shipping_all_price_condition_w').val(response.data);
                            }
                            $('.district_shipping_advance.all_condition_district_w').removeClass('loading');
                            loading = false;
                        }
                    });
                }
            });
        }
    })
})( jQuery, woocommerce_district_shipping_rate_rows, wp, ajaxurl );
