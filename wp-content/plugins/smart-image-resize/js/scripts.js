// Utils

var WP_SIR_UTIL = {
  setCookie: function (cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
};

(function ($) {
  'use strict';

  // ELEMENTS
  var $colorPicker = $('#wpSirColorPicker');
  var $compressImageSlider = $('.wpSirSlider');

  // ------------------------------------------------------------------------------------------
  // INITILIAZE COLOR PICKER
  // ------------------------------------------------------------------------------------------

  $colorPicker.wpColorPicker();

  // ------------------------------------------------------------------------------------------
  // INITILIAZE COMPRESSION SLIDER.
  // ------------------------------------------------------------------------------------------

  $compressImageSlider.each(function () {
    var handle = $(this).find('.wpSirSliderHandler');
    var inputElement = $('.' + $(this).data('input'));
    $(this).slider({
      create: function () {
        $(this).slider('value', inputElement.val());
        handle.text($(this).slider('value') + '%');
      },
      slide: function (event, ui) {
        handle.text(ui.value + '%');
        inputElement.val(ui.value);
      },
      change: function (event, ui) {
        handle.text(ui.value + '%');
      },
    });
  });

  // we'll wait until the box is rendered, so we can move it to the top.
  var wpsirLoadIntervalId = setInterval(() => {
    if ($('.wpsirProcessMediaLibraryImageWraper').length) {
      clearInterval(wpsirLoadIntervalId);

      $('.wpsirProcessMediaLibraryImageWraper')
        .insertBefore($('#wp-media-grid > .media-frame'));

      handleProcessMediaLibraryChange($('#processMediaLibraryImage'));

      $(document).on('change', '#processMediaLibraryImage', function () {
        handleProcessMediaLibraryChange($(this));
      });

    }
  }, 100);


  /**
   * Allow user to decide whether to process image being uploaded.
   * We'll place a checkbox input where we cannot determine image attachment parent
   * under "Media > Library" and "Media > Add" new pages.
   */


  function handleProcessMediaLibraryChange($input) {
    var isProcessable = $input.is(':checked');

    WP_SIR_UTIL.setCookie(wp_sir_object.process_ml_upload_cookie, isProcessable.toString(), 365);
    // Normal HTML uploader.
    if ($('#html-upload-ui').length) {
      var $htmlProcessableInput = $('input[name="_processable_image"]');

      if ($htmlProcessableInput.length === 0) {
        $('#html-upload-ui').append(
          '<input type="hidden"  name="_processable_image" >'
        );
        $htmlProcessableInput = $($htmlProcessableInput.selector);
      }
      $htmlProcessableInput.val(isProcessable);
    }

    // Drag-and-drop uploader box.
    if (
      typeof wpUploaderInit === 'object' &&
      wpUploaderInit.hasOwnProperty('multipart_params')
    ) {
      wpUploaderInit.multipart_params._processable_image = isProcessable;
    }

    // Media library modal.
    if (
      wp.media &&
      wp.media.frame &&
      wp.media.frame.uploader &&
      wp.media.frame.uploader.uploader
    ) {
      wp.media.frame.uploader.uploader.param('_processable_image', isProcessable);
    }
  }


  // Toggle the Trim option settings.
  $('#wp-sir-enable-trim').on('change', function () {
    if ($(this).is(':checked')) {
      $('#wp-sir-trim-feather-wrap').removeClass('hidden');
      $('#wp-sir-trim-tolerance-wrap').removeClass('hidden');
    } else {
      $('#wp-sir-trim-feather-wrap').addClass('hidden');
      $('#wp-sir-trim-tolerance-wrap').addClass('hidden');
    }

  }).change();


  // Reset "Image sizes" to default ones.
  $(document).on('click', '#wpsirResetDefaultSizes', function () {
    var preselectedSizes = $('#wp-sir-sizes-selector').data('defaults').split(',');
    $('.wpSirSelectSize').each(function () {
      if (preselectedSizes.indexOf($(this).val()) >= 0) {
        $(this).prop('checked', true).change();
      } else {
        $(this).prop('checked', false).change();
      }
    });
  });


  // Add filter to Media Library (grid view)

  if (typeof sir_vars != 'undefined') {
    var SIR_MediaLibraryTaxonomyFilter = wp.media.view.AttachmentFilters.extend({
      id: 'media-attachment-sir-filter',
      createFilters() {
        this.filters = {
          all: {
            text: sir_vars.filter_strings.all,
            props: { _filter: 'all' },
            priority: 10,
          },
          processed: {
            text: sir_vars.filter_strings.processed,
            props: { _filter: 'processed' },
            priority: 20,
          },
          unprocessed: {
            text: sir_vars.filter_strings.unprocessed,
            props: { _filter: 'unprocessed' },
            priority: 30,
          },
        };
      },
    });

    var SIR_AttachmentsBrowser = wp.media.view.AttachmentsBrowser;
    wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
      createToolbar() {
        // Make sure to load the original toolbar
        SIR_AttachmentsBrowser.prototype.createToolbar.call(this);
        this.toolbar.set(
          'SIR_MediaLibraryTaxonomyFilter',
          new SIR_MediaLibraryTaxonomyFilter({
            controller: this.controller,
            model: this.collection.props,
            priority: -75,
          }).render()
        );
      },
    });

  }

  // Handle the "Clear" button display.
  $('#wp-sir-clear-bg-color').on('click', function (e) {
    $colorPicker.find('.wp-picker-clear').click();
    $colorPicker.val('').trigger('change');
    $colorPicker.find('.wp-color-result').css({ 'background-color': '' });
    e.preventDefault();
    e.stopPropagation();
    $(this).hide();
  });

  if (!$colorPicker.val()) {
    $('#wp-sir-clear-bg-color').hide();
  }

  $('.wp-picker-container').on('click', function () {
    if ($(this).hasClass('wp-picker-active')) {
      $('#wp-sir-clear-bg-color').hide();
    } else if ($colorPicker.val()) {
      $('#wp-sir-clear-bg-color').show();
    }
  });


  $(document).on('click', function (e) {
    if ($colorPicker.val()) {
      $('#wp-sir-clear-bg-color').show();
    }
  });


  $(document).on('change', '.wpSirSelectSize', function () {
    
    $(this).closest('tr').find('input[type="number"]').prop('disabled', !$(this).is(':checked'));
    $(this).closest('tr').find('.wp-sir-fit-mode').prop('disabled', !$(this).is(':checked'));
    if ($(this).closest('tr').find('.wp-sir-fit-mode').is(':checked')) {
      $(this).closest('tr').find('input[type="number"]').prop('disabled', true);
    }
    var isAllSizesSelected = $('.wpSirSelectSize:checked').length === $('.wpSirSelectSize').length;
    $('#wp-sir-toggle-all-sizes').prop('checked', isAllSizesSelected);
  });

  $('.wpSirSelectSize').each(function () {
   
    $(this).closest('tr').find('input[type=number]').prop('disabled', !$(this).is(':checked')).change();
    $(this).closest('tr').find('.wp-sir-fit-mode').prop('disabled', !$(this).is(':checked')).change();
    $(this).closest('tr').find('.wp-sir-fit-mode').each(function () {
      if ($(this).is(':checked')) {
        $(this).closest('tr').find('input[type=number]').prop('disabled', true);
      }
    });
  });

  $('#wp-sir-toggle-all-sizes').on('change', function () {
    var $toggle = $(this);
    $('.wpSirSelectSize').each(function(){
      if(! $(this).is(':disabled')){
        $(this).prop('checked', $toggle.is(':checked'));

      }
    });
    $('.wpSirSelectSize').change();
  });

  $('.wp-sir-fit-mode').on('change', function () {
    if ($(this).is(':checked') ) {
      $(this).closest('tr').find('.wp-sir-custom-dimensions').find('input').prop('disabled', true);
    } else {
      $(this).closest('tr').find('.wp-sir-custom-dimensions').find('input').prop('disabled', false);
    }
  });

  if ($.fn.tipTip) {
    $('.wp-sir-help-tip').tipTip();
  }


  $(document).on('click', '#wp-sir-open-media-uploader', function (e) {
    var frame;

    if (frame) {
      frame.open();
      return;
    }

    frame = wp.media({
      title: 'Select or Upload Watermark image',
      multiple: false,
    });

    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      var $watermarkImageInput = $('input[name="wp_sir_settings[watermark_image]"]');
      $watermarkImageInput.val(attachment.id);
      $watermarkImageInput.data('size', { w: attachment.width, h: attachment.height });

      var $watermarkPreview = $('.wp-sir-watermark-preview-container');

      if ($watermarkPreview.find('img').length) {
        $watermarkPreview.find('img').attr('src', attachment.url);
      } else {
        $watermarkPreview.append('<img src="' + attachment.url + '"/>');
        $watermarkPreview.find('img').css('position', 'absolute');
      }

      var previewSize = { w: $watermarkPreview.width(), h: $watermarkPreview.height() };
      var h,w;
      var size = +$('.wp-sir-watermark-size').val();

      if (attachment.width >= attachment.height) {
        w = previewSize.w * size /100;
        if (w >= previewSize.w) {
          w = previewSize.w;
        }
        h = attachment.height * w / attachment.width;
      } else {
        h = previewSize.h * size / 100;
        if (h >= previewSize.h) {
          h = previewSize.h
        }
        w = attachment.width * h / attachment.height;
      }

      if(w >= previewSize.w) {
        w = previewSize.w;
        h = attachment.height * w / attachment.width;
      }

      if(h >= previewSize.h) {
        h = previewSize.h;
        w = attachment.width * h / attachment.height;
      }
      
      $watermarkPreview.find('img').css({ width: w + 'px', height: h + 'px' });
      $watermarkPreview.find('img').css({ opacity: +$('.wp-sir-watermark-opacity').val()/100 });
      $('#wp-sir-watermark-position').trigger('change');
    });

    frame.open();

  });


  // Handle watermark slider change.
  $('.wp-sir-watermark-size-slider').each(function () {
    var $slider = $(this);
    var $handle, // The slider handle
      watermarkSize,
      $watermarkSizeInput,// The watermark size
      $previewImageContainer, // The Preview image container
      previewSize, // The preview image size
      $watermark, // The watermark image
      $watermarkImageInput, // The watermark image ID
      watermarkNewHeight,
      watermarkNewWidth;

    $slider.slider({
      min: 1,
      create: function (event, ui) {
        $handle = $slider.find('.wp-sir-watermark-size-slider-handler');
        $watermarkImageInput = $('input[name="wp_sir_settings[watermark_image]"]');
        $watermarkSizeInput = $('input[name="wp_sir_settings[watermark_size]"]');

        $previewImageContainer = $('.wp-sir-watermark-preview-container');
        previewSize = { w: $previewImageContainer.width(), h: $previewImageContainer.height() };

        $watermarkSizeInput = $('.' + $slider.data('input'));
        var initValue = $watermarkSizeInput.val();
        
        $(this).slider('value', initValue);
        $handle.text($(this).slider('value') + '%');
        $watermark = $previewImageContainer.find('img');

        if (!$watermark.length) {
          return;
        }
        watermarkSize = {w: $watermark.width(), h: $watermark.height()};

        if (watermarkSize.w >= watermarkSize.h) {
          watermarkNewWidth = previewSize.w * $(this).slider('value') / 100;
          if (watermarkNewWidth >= previewSize.w) {
            watermarkNewWidth = previewSize.w;
          }
          watermarkNewHeight = watermarkSize.h * watermarkNewWidth / watermarkSize.w;
        } else {
          watermarkNewHeight = previewSize.h * $(this).slider('value') / 100;
          if (watermarkNewHeight >= previewSize.h) {
            watermarkNewHeight = previewSize.h
          }
          watermarkNewWidth = watermarkSize.w * watermarkNewHeight / watermarkSize.h;
        }


        if(watermarkNewWidth >= previewSize.w) {
          watermarkNewWidth = previewSize.w;
          watermarkNewHeight = watermarkSize.h * watermarkNewWidth / watermarkSize.w;
        }

        if(watermarkNewHeight >= previewSize.h) {
          watermarkNewHeight = previewSize.h;
          watermarkNewWidth = watermarkSize.w * watermarkNewHeight / watermarkSize.h;
        }


        $watermark.css({ width: watermarkNewWidth + 'px', height: watermarkNewHeight + 'px' });
      },
      slide: function (event, ui) {
        $(this).slider('value', ui.value);
        $handle.text(ui.value + '%');
        $watermarkSizeInput.val(ui.value);

        $watermark = $previewImageContainer.find('img');

        if (!$watermark.length) {
          return;
        }
        watermarkSize = {w: $watermark.width(), h: $watermark.height()};
        console.log(watermarkSize);
        previewSize = { w: $previewImageContainer.width(), h: $previewImageContainer.height() };

        if (watermarkSize.w >= watermarkSize.h) {
          watermarkNewWidth = previewSize.w * ui.value / 100;
          if (watermarkNewWidth >= previewSize.w) {
            watermarkNewWidth = previewSize.w;
          }
          watermarkNewHeight = watermarkSize.h * watermarkNewWidth / watermarkSize.w;
        } else {
          watermarkNewHeight = watermarkSize.h * ui.value / 100;
          if (watermarkNewHeight >= previewSize.h) {
            watermarkNewHeight = previewSize.h
          }
          watermarkNewWidth = watermarkSize.w * watermarkNewHeight / watermarkSize.h;
        }

        if(watermarkNewWidth >= previewSize.w) {
          watermarkNewWidth = previewSize.w;
          watermarkNewHeight = watermarkSize.h * watermarkNewWidth / watermarkSize.w;
        }

        if(watermarkNewHeight >= previewSize.h) {
          watermarkNewHeight = previewSize.h;
          watermarkNewWidth = watermarkSize.w * watermarkNewHeight / watermarkSize.h;
        }
        $watermark.css({ width: watermarkNewWidth + 'px', height: watermarkNewHeight + 'px' });
      },
      change: function (event, ui) {
        $handle.text(ui.value + '%');
      },
    });
  });

  $(document).on('change', '#wp-sir-watermark-position', function () {
    setWatermarkPosition($(this));
  });


  $('.wp-sir-watermark-opacity-slider').each(function () {
    var $this = $(this);
    var $handle,
      $previewImageContainer,
      $watermark,
      $opacityInput;

    $(this).slider({
      min: 1,
      create: function () {
        $handle = $this.find('.wp-sir-watermark-opacity-slider-handler');
        $opacityInput = $('.' + $this.data('input'));
        $(this).slider('value', $opacityInput.val());
        $handle.text($(this).slider('value') + '%');
        $previewImageContainer = $('.wp-sir-watermark-preview-container');
        $watermark = $previewImageContainer.find('img');

        if ($watermark.length) {
          $watermark.css({ opacity: $opacityInput.val() / 100 });
        }

      },
      slide: function (event, ui) {

        $handle.text(ui.value + '%');
        $opacityInput.val(ui.value);

        $watermark = $previewImageContainer.find('img');
        if ($watermark.length) {
          $watermark.css({ opacity: ui.value / 100 });
        }

      },
      change: function (event, ui) {
        $handle.text(ui.value + '%');
      },
    });
  });

  function setWatermarkPosition($element) {
    var $img = $('.wp-sir-watermark-preview-container').find('img');
    var $offset_y = $('#wp-sir-watermark-offset-y');
    var $offset_x = $('#wp-sir-watermark-offset-x');
    var offset_x = $offset_x.val();
    var offset_y = $offset_y.val();
    switch ($element.val()) {
      case 'top-left':
        $img.css({
          'top': '0px',
          'left': '0px',
          'right': 'auto',
          'bottom': 'auto',
          'transform': 'translate(' + offset_x + 'px, ' + offset_y + 'px)'
        });
        $offset_x.removeAttr('disabled');
        $offset_y.removeAttr('disabled');
        break;
      case 'top-right':
        $img.css({
          'top': '0px',
          'left': 'auto',
          'right': '0px',
          'bottom': 'auto',
          'transform': 'translate(-' + offset_x + 'px, ' + offset_y + 'px)'
        });
        $offset_x.removeAttr('disabled');
        $offset_y.removeAttr('disabled');
        break;
      case 'bottom-left':
        $img.css({
          'top': 'auto',
          'left': '0px',
          'right': 'auto',
          'bottom': '0px',
          'transform': 'translate(' + offset_x + 'px, -' + offset_y + 'px)'
        });
        $offset_x.removeAttr('disabled');
        $offset_y.removeAttr('disabled');
        break;
      case 'bottom-right':
        $img.css({
          'top': 'auto',
          'left': 'auto',
          'right': '0px',
          'bottom': '0px',
          'transform': 'translate(-' + offset_x + 'px, -' + offset_y + 'px)'
        });
        $offset_x.removeAttr('disabled');
        $offset_y.removeAttr('disabled');
        break;
      case 'center':
        $img.css({
          'top': '50%',
          'left': '50%',
          'right': 'auto',
          'bottom': 'auto',
          'transform': 'translate(-50%, -50%)'
        });
        $offset_x.prop('disabled', true);
        $offset_y.prop('disabled', true);
        break;
    }
  }

  setWatermarkPosition($('#wp-sir-watermark-position'));

  $(document).on('change keyup paste', '#wp-sir-watermark-offset-x', function () {

    if ($('#wp-sir-watermark-position').val() == 'center') {
      return;
    }
    var offset_x = $(this).val();
    var offset_y = +$('#wp-sir-watermark-offset-y').val();


    if ($('#wp-sir-watermark-position').val() == 'bottom-right' || $('#wp-sir-watermark-position').val() == 'top-right') {
      offset_x = -offset_x;
    }

    if ($('#wp-sir-watermark-position').val() == 'bottom-left' || $('#wp-sir-watermark-position').val() == 'bottom-right') {
      offset_y = -offset_y;
    }

    $('.wp-sir-watermark-preview-container')
      .find('img')
      .css('transform', 'translate(' + offset_x + 'px, ' + offset_y + 'px)');
  }).change();

  $(document).on('change keyup paste', '#wp-sir-watermark-offset-y', function () {
    var offset_y = $(this).val();
    var offset_x = +$('#wp-sir-watermark-offset-x').val();

    if ($('#wp-sir-watermark-position').val() == 'bottom-right' || $('#wp-sir-watermark-position').val() == 'top-right') {
      offset_x = -offset_x;
    }
    if ($('#wp-sir-watermark-position').val() == 'bottom-left' || $('#wp-sir-watermark-position').val() == 'bottom-right') {
      offset_y = -offset_y;
    }
    $('.wp-sir-watermark-preview-container')
      .find('img')
      .css('transform', 'translate(' + offset_x + 'px, ' + offset_y + 'px)');
  }).change();

  $(document).on('change', '#wp-sir-enable-watermark', function () {
    if ($(this).is(':checked')) {
      $('.wp-sir-watermark-settings').css('display', 'flex');
    } else {
      $('.wp-sir-watermark-settings').css('display', 'none');

    }
  }).change();

})(jQuery);



