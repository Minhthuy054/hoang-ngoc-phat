/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 6);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/public/components/dashboard/dashBoardMoreBtn.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashBoardMoreBtn.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  // User Dashboard Table More Button
  $('.directorist-dashboard-listings-tbody').on("click", '.directorist-btn-more', function (e) {
    e.preventDefault();
    $(this).toggleClass('active');
    $(".directorist-dropdown-menu").removeClass("active");
    $(this).next(".directorist-dropdown-menu").toggleClass("active");
    e.stopPropagation();
  });
  $(document).bind("click", function (e) {
    if (!$(e.target).parents().hasClass('directorist-dropdown-menu__list')) {
      $(".directorist-dropdown-menu").removeClass("active");
      $(".directorist-btn-more").removeClass("active");
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardAnnouncement.js":
/*!****************************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardAnnouncement.js ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  // Clear seen Announcements
  var cleared_seen_announcements = false;
  $('.directorist-tab__nav__link').on('click', function () {
    if (cleared_seen_announcements) {
      return;
    }

    var target = $(this).attr('target');

    if ('dashboard_announcement' === target) {
      // console.log( target, 'clear seen announcements' );
      $.ajax({
        type: "post",
        url: directorist.ajaxurl,
        data: {
          action: 'atbdp_clear_seen_announcements'
        },
        success: function success(response) {
          // console.log( response );
          if (response.success) {
            cleared_seen_announcements = true;
            $('.directorist-announcement-count').removeClass('show');
            $('.directorist-announcement-count').html('');
          }
        },
        error: function error(_error) {
          console.log({
            error: _error
          });
        }
      });
    }
  }); // Closing the Announcement

  var closing_announcement = false;
  $('.close-announcement').on('click', function (e) {
    e.preventDefault();

    if (closing_announcement) {
      console.log('Please wait...');
      return;
    }

    var post_id = $(this).closest('.directorist-announcement').data('post-id');
    var form_data = {
      action: 'atbdp_close_announcement',
      post_id: post_id
    };
    var button_default_html = $(self).html();
    closing_announcement = true;
    var self = this;
    $.ajax({
      type: "post",
      url: directorist.ajaxurl,
      data: form_data,
      beforeSend: function beforeSend() {
        $(self).html('<span class="fas fa-spinner fa-spin"></span> ');
        $(self).addClass('disable');
        $(self).attr('disable', true);
      },
      success: function success(response) {
        // console.log( { response } );
        closing_announcement = false;
        $(self).removeClass('disable');
        $(self).attr('disable', false);

        if (response.success) {
          $('.announcement-id-' + post_id).remove();

          if (!$('.announcement-item').length) {
            location.reload();
          }
        } else {
          $(self).html('Close');
        }
      },
      error: function error(_error2) {
        console.log({
          error: _error2
        });
        $(self).html(button_default_html);
        $(self).removeClass('disable');
        $(self).attr('disable', false);
        closing_announcement = false;
      }
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardBecomeAuthor.js":
/*!****************************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardBecomeAuthor.js ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  // Dashboard become an author
  $('.directorist-become-author').on('click', function (e) {
    e.preventDefault();
    $(".directorist-become-author-modal").addClass("directorist-become-author-modal__show");
  });
  $('.directorist-become-author-modal__cancel').on('click', function (e) {
    e.preventDefault();
    $(".directorist-become-author-modal").removeClass("directorist-become-author-modal__show");
  });
  $('.directorist-become-author-modal__approve').on('click', function (e) {
    e.preventDefault();
    var userId = $(this).attr('data-userId');
    var nonce = $(this).attr('data-nonce');
    var data = {
      userId: userId,
      nonce: nonce,
      action: "atbdp_become_author"
    }; // Send the data

    $.post(directorist.ajaxurl, data, function (response) {
      $('.directorist-become-author__loader').addClass('active');
      $('#directorist-become-author-success').html(response);
      $('.directorist-become-author').hide();
      $(".directorist-become-author-modal").removeClass("directorist-become-author-modal__show");
    });
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardListing.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardListing.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  // Dashboard Listing Ajax
  function directorist_dashboard_listing_ajax($activeTab) {
    var paged = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;
    var search = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
    var task = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '';
    var taskdata = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : '';
    var tab = $activeTab.data('tab');
    $.ajax({
      url: directorist.ajaxurl,
      type: 'POST',
      dataType: 'json',
      data: {
        'action': 'directorist_dashboard_listing_tab',
        'tab': tab,
        'paged': paged,
        'search': search,
        'task': task,
        'taskdata': taskdata
      },
      beforeSend: function beforeSend() {
        $('#directorist-dashboard-preloader').show();
      },
      success: function success(response) {
        $('.directorist-dashboard-listings-tbody').html(response.data.content);
        $('.directorist-dashboard-pagination').html(response.data.pagination);
        $('.directorist-dashboard-listing-nav-js a').removeClass('directorist-tab__nav__active');
        $activeTab.addClass('directorist-tab__nav__active');
        $('#directorist-dashboard-mylistings-js').data('paged', paged);
      },
      complete: function complete() {
        $('#directorist-dashboard-preloader').hide();
      }
    });
  } // Dashboard Listing Tabs


  $('.directorist-dashboard-listing-nav-js a').on('click', function (event) {
    var $item = $(this);

    if ($item.hasClass('directorist-tab__nav__active')) {
      return false;
    }

    directorist_dashboard_listing_ajax($item);
    $('#directorist-dashboard-listing-searchform input[name=searchtext').val('');
    $('#directorist-dashboard-mylistings-js').data('search', '');
    return false;
  }); // Dashboard Tasks eg. delete

  $('.directorist-dashboard-listings-tbody').on('click', '.directorist-dashboard-listing-actions a[data-task]', function (event) {
    var task = $(this).data('task');
    var postid = $(this).closest('tr').data('id');
    var $activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
    var paged = $('#directorist-dashboard-mylistings-js').data('paged');
    var search = $('#directorist-dashboard-mylistings-js').data('search');

    if (task == 'delete') {
      swal({
        title: directorist.listing_remove_title,
        text: directorist.listing_remove_text,
        type: "warning",
        cancelButtonText: directorist.review_cancel_btn_text,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: directorist.listing_remove_confirm_text,
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }, function (isConfirm) {
        if (isConfirm) {
          directorist_dashboard_listing_ajax($activeTab, paged, search, task, postid);
          swal({
            title: directorist.listing_delete,
            type: "success",
            timer: 200,
            showConfirmButton: false
          });
        }
      });
    }

    return false;
  }); // Remove Listing

  $(document).on('click', '#remove_listing', function (e) {
    e.preventDefault();
    var $this = $(this);
    var id = $this.data('listing_id');
    var data = 'listing_id=' + id;
    swal({
      title: directorist.listing_remove_title,
      text: directorist.listing_remove_text,
      type: "warning",
      cancelButtonText: directorist.review_cancel_btn_text,
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: directorist.listing_remove_confirm_text,
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }, function (isConfirm) {
      if (isConfirm) {
        // user has confirmed, now remove the listing
        atbdp_do_ajax($this, 'remove_listing', data, function (response) {
          $('body').append(response);

          if ('success' === response) {
            // show success message
            swal({
              title: directorist.listing_delete,
              type: "success",
              timer: 200,
              showConfirmButton: false
            });
            $("#listing_id_" + id).remove();
            $this.remove();
          } else {
            // show error message
            swal({
              title: directorist.listing_error_title,
              text: directorist.listing_error_text,
              type: "error",
              timer: 2000,
              showConfirmButton: false
            });
          }
        });
      }
    }); // send an ajax request to the ajax-handler.php and then delete the review of the given id
  }); // Dashboard pagination

  $('.directorist-dashboard-pagination').on('click', 'a', function (event) {
    var $link = $(this);
    var paged = $link.attr('href');
    paged = paged.split('/page/')[1];
    paged = parseInt(paged);
    var search = $('#directorist-dashboard-mylistings-js').data('search');
    $activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
    directorist_dashboard_listing_ajax($activeTab, paged, search);
    return false;
  }); // Dashboard Search

  $('#directorist-dashboard-listing-searchform input[name=searchtext').val(''); //onready

  $('#directorist-dashboard-listing-searchform').on('submit', function (event) {
    var $activeTab = $('.directorist-dashboard-listing-nav-js a.directorist-tab__nav__active');
    var search = $(this).find('input[name=searchtext]').val();
    directorist_dashboard_listing_ajax($activeTab, 1, search);
    $('#directorist-dashboard-mylistings-js').data('search', search);
    return false;
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardResponsive.js":
/*!**************************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardResponsive.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  //dashboard content responsive fix
  var tabContentWidth = $(".directorist-user-dashboard .directorist-user-dashboard__contents").innerWidth();

  if (tabContentWidth < 1399) {
    $(".directorist-user-dashboard .directorist-user-dashboard__contents").addClass("directorist-tab-content-grid-fix");
  }

  $(window).bind("resize", function () {
    if ($(this).width() <= 1199) {
      $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed");
      $(".directorist-shade").removeClass("directorist-active");
    }
  }).trigger("resize");
  $('.directorist-dashboard__nav--close, .directorist-shade').on('click', function () {
    $(".directorist-user-dashboard__nav").addClass('directorist-dashboard-nav-collapsed');
    $(".directorist-shade").removeClass("directorist-active");
  }); // Profile Responsive

  $('.directorist-tab__nav__link').on('click', function () {
    if ($('#user_profile_form').width() < 800 && $('#user_profile_form').width() !== 0) {
      $('#user_profile_form').addClass('directorist-profile-responsive');
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardSidebar.js":
/*!***********************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardSidebar.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  //dashboard sidebar nav toggler
  $(".directorist-user-dashboard__toggle__link").on("click", function (e) {
    e.preventDefault();
    $(".directorist-user-dashboard__nav").toggleClass("directorist-dashboard-nav-collapsed"); // $(".directorist-shade").toggleClass("directorist-active");
  });

  if ($(window).innerWidth() < 767) {
    $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed");
    $(".directorist-user-dashboard__nav").addClass("directorist-dashboard-nav-collapsed--fixed");
  } //dashboard nav dropdown


  $(".atbdp_tab_nav--has-child .atbd-dash-nav-dropdown").on("click", function (e) {
    e.preventDefault();
    $(this).siblings("ul").slideToggle();
  });

  if ($(window).innerWidth() < 1199) {
    $(".directorist-tab__nav__link").on("click", function () {
      $(".directorist-user-dashboard__nav").addClass('directorist-dashboard-nav-collapsed');
      $(".directorist-shade").removeClass("directorist-active");
    });
    $(".directorist-user-dashboard__toggle__link").on("click", function (e) {
      e.preventDefault();
      $(".directorist-shade").toggleClass("directorist-active");
    });
  }
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/dashboard/dashboardTab.js":
/*!*******************************************************************!*\
  !*** ./assets/src/js/public/components/dashboard/dashboardTab.js ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  // User Dashboard Tab
  $(function () {
    var hash = window.location.hash;
    var selectedTab = $('.navbar .menu li a [target= "' + hash + '"]');
  }); // store the currently selected tab in the hash value

  $("ul.directorist-tab__nav__items > li > a.directorist-tab__nav__link").on("click", function (e) {
    var id = $(e.target).attr("target").substr();
    window.location.hash = "#active_" + id;
    e.stopPropagation();
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/directoristDropdown.js":
/*!****************************************************************!*\
  !*** ./assets/src/js/public/components/directoristDropdown.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* custom dropdown */
var atbdDropdown = document.querySelectorAll('.directorist-dropdown-select'); // toggle dropdown

var clickCount = 0;

if (atbdDropdown !== null) {
  atbdDropdown.forEach(function (el) {
    el.querySelector('.directorist-dropdown-select-toggle').addEventListener('click', function (e) {
      e.preventDefault();
      clickCount++;

      if (clickCount % 2 === 1) {
        document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elem) {
          elem.classList.remove('directorist-dropdown-select-show');
        });
        el.querySelector('.directorist-dropdown-select-items').classList.add('directorist-dropdown-select-show');
      } else {
        document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elem) {
          elem.classList.remove('directorist-dropdown-select-show');
        });
      }
    });
  });
} // remvoe toggle when click outside


document.body.addEventListener('click', function (e) {
  if (e.target.getAttribute('data-drop-toggle') !== 'directorist-dropdown-select-toggle') {
    clickCount = 0;
    document.querySelectorAll('.directorist-dropdown-select-items').forEach(function (el) {
      el.classList.remove('directorist-dropdown-select-show');
    });
  }
}); //custom select

var atbdSelect = document.querySelectorAll('.atbd-drop-select');

if (atbdSelect !== null) {
  atbdSelect.forEach(function (el) {
    el.querySelectorAll('.directorist-dropdown-select-items').forEach(function (item) {
      item.addEventListener('click', function (e) {
        e.preventDefault();
        el.querySelector('.directorist-dropdown-select-toggle').textContent = e.target.textContent;
        el.querySelectorAll('.directorist-dropdown-select-items').forEach(function (elm) {
          elm.classList.remove('atbd-active');
        });
        item.classList.add('atbd-active');
      });
    });
  });
}

;

(function ($) {
  // Dropdown
  $('body').on('click', '.directorist-dropdown .directorist-dropdown-toggle', function (e) {
    e.preventDefault();
    $(this).siblings('.directorist-dropdown-option').toggle();
  }); // Select Option after click

  $('body').on('click', '.directorist-dropdown .directorist-dropdown-option ul li a', function (e) {
    e.preventDefault();
    var optionText = $(this).html();
    $(this).children('.directorist-dropdown-toggle__text').html(optionText);
    $(this).closest('.directorist-dropdown-option').siblings('.directorist-dropdown-toggle').children('.directorist-dropdown-toggle__text').html(optionText);
    $('.directorist-dropdown-option').hide();
  }); // Hide Clicked Anywhere

  $(document).bind('click', function (e) {
    var clickedDom = $(e.target);
    if (!clickedDom.parents().hasClass('directorist-dropdown')) $('.directorist-dropdown-option').hide();
  }); //atbd_dropdown

  $(document).on("click", '.atbd_dropdown', function (e) {
    if ($(this).attr("class") === "atbd_dropdown") {
      e.preventDefault();
      $(this).siblings(".atbd_dropdown").removeClass("atbd_drop--active");
      $(this).toggleClass("atbd_drop--active");
      e.stopPropagation();
    }
  });
  $(document).on("click", function (e) {
    if ($(e.target).is(".atbd_dropdown, .atbd_drop--active") === false) {
      $(".atbd_dropdown").removeClass("atbd_drop--active");
    }
  });
  $('body').on('click', '.atbd_dropdown-toggle', function (e) {
    e.preventDefault();
  }); // Directorist Dropdown

  $('body').on('click', '.directorist-dropdown-js .directorist-dropdown__toggle-js', function (e) {
    e.preventDefault();

    if (!$(this).siblings('.directorist-dropdown__links-js').is(':visible')) {
      $('.directorist-dropdown__links').hide();
    }

    $(this).siblings('.directorist-dropdown__links-js').toggle();
  });
  $('body').on('click', function (e) {
    if (!e.target.closest('.directorist-dropdown-js')) {
      $('.directorist-dropdown__links-js').hide();
    }
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/directoristSelect.js":
/*!**************************************************************!*\
  !*** ./assets/src/js/public/components/directoristSelect.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

//custom select
var atbdSelect = document.querySelectorAll('.atbd-drop-select');

if (atbdSelect !== null) {
  atbdSelect.forEach(function (el) {
    el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
      item.addEventListener('click', function (e) {
        e.preventDefault();
        el.querySelector('.atbd-dropdown-toggle').textContent = item.textContent;
        el.querySelectorAll('.atbd-dropdown-item').forEach(function (elm) {
          elm.classList.remove('atbd-active');
        });
        item.classList.add('atbd-active');
      });
    });
  });
} // select data-status


var atbdSelectData = document.querySelectorAll('.atbd-drop-select.with-sort');
atbdSelectData.forEach(function (el) {
  el.querySelectorAll('.atbd-dropdown-item').forEach(function (item) {
    var ds = el.querySelector('.atbd-dropdown-toggle');
    var itemds = item.getAttribute('data-status');
    item.addEventListener('click', function (e) {
      ds.setAttribute('data-status', "".concat(itemds));
    });
  });
});

/***/ }),

/***/ "./assets/src/js/public/components/legacy-support.js":
/*!***********************************************************!*\
  !*** ./assets/src/js/public/components/legacy-support.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* custom dropdown */
var atbdDropdown = document.querySelectorAll('.atbd-dropdown'); // toggle dropdown

var clickCount = 0;

if (atbdDropdown !== null) {
  atbdDropdown.forEach(function (el) {
    el.querySelector('.atbd-dropdown-toggle').addEventListener('click', function (e) {
      e.preventDefault();
      clickCount++;

      if (clickCount % 2 === 1) {
        document.querySelectorAll('.atbd-dropdown-items').forEach(function (elem) {
          elem.classList.remove('atbd-show');
        });
        el.querySelector('.atbd-dropdown-items').classList.add('atbd-show');
      } else {
        document.querySelectorAll('.atbd-dropdown-items').forEach(function (elem) {
          elem.classList.remove('atbd-show');
        });
      }
    });
  });
} // remvoe toggle when click outside


document.body.addEventListener('click', function (e) {
  if (e.target.getAttribute('data-drop-toggle') !== 'atbd-toggle') {
    clickCount = 0;
    document.querySelectorAll('.atbd-dropdown-items').forEach(function (el) {
      el.classList.remove('atbd-show');
    });
  }
});

/***/ }),

/***/ "./assets/src/js/public/components/profileForm.js":
/*!********************************************************!*\
  !*** ./assets/src/js/public/components/profileForm.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

;

(function ($) {
  var profileMediaUploader = null;

  if ($("#user_profile_pic").length) {
    profileMediaUploader = new EzMediaUploader({
      containerID: "user_profile_pic"
    });
    profileMediaUploader.init();
  }

  var is_processing = false;
  $('#user_profile_form').on('submit', function (e) {
    // submit the form to the ajax handler and then send a response from the database and then work accordingly and then after finishing the update profile then work on remove listing and also remove the review and rating form the custom table once the listing is deleted successfully.
    e.preventDefault();
    var submit_button = $('#update_user_profile');
    submit_button.attr('disabled', true);
    submit_button.addClass("directorist-loader");

    if (is_processing) {
      submit_button.removeAttr('disabled');
      return;
    }

    var form_data = new FormData();
    var err_log = {};
    var error_count; // ajax action

    form_data.append('action', 'update_user_profile');
    form_data.append('directorist_nonce', directorist.directorist_nonce);

    if (profileMediaUploader) {
      var hasValidFiles = profileMediaUploader.hasValidFiles();

      if (hasValidFiles) {
        //files
        var files = profileMediaUploader.getTheFiles();
        var filesMeta = profileMediaUploader.getFilesMeta();

        if (files.length) {
          for (var i = 0; i < files.length; i++) {
            form_data.append('profile_picture', files[i]);
          }
        }

        if (filesMeta.length) {
          for (var i = 0; i < filesMeta.length; i++) {
            var elm = filesMeta[i];

            for (var key in elm) {
              form_data.append('profile_picture_meta[' + i + '][' + key + ']', elm[key]);
            }
          }
        }
      } else {
        $(".directorist-form-submit__btn").removeClass("atbd_loading");
        err_log.user_profile_avater = {
          msg: 'Listing gallery has invalid files'
        };
        error_count++;
      }
    }

    var $form = $(this);
    var arrData = $form.serializeArray();
    $.each(arrData, function (index, elem) {
      var name = elem.name;
      var value = elem.value;
      form_data.append(name, value);
    });
    $.ajax({
      method: 'POST',
      processData: false,
      contentType: false,
      url: directorist.ajaxurl,
      data: form_data,
      success: function success(response) {
        submit_button.removeAttr('disabled');
        submit_button.removeClass("directorist-loader");
        console.log(response);

        if (response.success) {
          $('#directorist-prifile-notice').html('<span class="directorist-alert directorist-alert-success">' + response.data + '</span>');
        } else {
          $('#directorist-prifile-notice').html('<span class="directorist-alert directorist-alert-danger">' + response.data + '</span>');
        }
      },
      error: function error(response) {
        submit_button.removeAttr('disabled');
        console.log(response);
      }
    }); // remove notice after five second

    setTimeout(function () {
      $("#directorist-prifile-notice .directorist-alert").remove();
    }, 5000); // prevent the from submitting

    return false;
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/js/public/components/pureScriptTab.js":
/*!**********************************************************!*\
  !*** ./assets/src/js/public/components/pureScriptTab.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
    Plugin: PureScriptTab
    Version: 1.0.0
    License: MIT
*/
var $ = jQuery;

pureScriptTab = function pureScriptTab(selector1) {
  var selector = document.querySelectorAll(selector1);
  selector.forEach(function (el, index) {
    a = el.querySelectorAll('.directorist-tab__nav__link');
    a.forEach(function (element, index) {
      element.style.cursor = 'pointer';
      element.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        var ul = event.target.closest('.directorist-tab__nav'),
            main = ul.nextElementSibling,
            item_a = ul.querySelectorAll('.directorist-tab__nav__link'),
            section = main.querySelectorAll('.directorist-tab__pane');
        item_a.forEach(function (ela, ind) {
          ela.classList.remove('directorist-tab__nav__active');
        });
        event.target.classList.add('directorist-tab__nav__active');
        section.forEach(function (element1, index) {
          //console.log(element1);
          element1.classList.remove('directorist-tab__pane--active');
        });
        var target = event.target.target;
        document.getElementById(target).classList.add('directorist-tab__pane--active');
      });
    });
  });
};
/* pureScriptTabChild = (selector1) => {
    var selector = document.querySelectorAll(selector1);
    selector.forEach((el, index) => {
        a = el.querySelectorAll('.pst_tn_link');


        a.forEach((element, index) => {

            element.style.cursor = 'pointer';
            element.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                var ul = event.target.closest('.pst_tab_nav'),
                    main = ul.nextElementSibling,
                    item_a = ul.querySelectorAll('.pst_tn_link'),
                    section = main.querySelectorAll('.pst_tab_inner');

                item_a.forEach((ela, ind) => {
                    ela.classList.remove('pstItemActive');
                });
                event.target.classList.add('pstItemActive');


                section.forEach((element1, index) => {
                    //console.log(element1);
                    element1.classList.remove('pstContentActive');
                });
                var target = event.target.target;
                document.getElementById(target).classList.add('pstContentActive');
            });
        });
    });
};

pureScriptTabChild2 = (selector1) => {
    var selector = document.querySelectorAll(selector1);
    selector.forEach((el, index) => {
        a = el.querySelectorAll('.pst_tn_link-2');


        a.forEach((element, index) => {

            element.style.cursor = 'pointer';
            element.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                var ul = event.target.closest('.pst_tab_nav-2'),
                    main = ul.nextElementSibling,
                    item_a = ul.querySelectorAll('.pst_tn_link-2'),
                    section = main.querySelectorAll('.pst_tab_inner-2');

                item_a.forEach((ela, ind) => {
                    ela.classList.remove('pstItemActive2');
                });
                event.target.classList.add('pstItemActive2');


                section.forEach((element1, index) => {
                    //console.log(element1);
                    element1.classList.remove('pstContentActive2');
                });
                var target = event.target.target;
                document.getElementById(target).classList.add('pstContentActive2');
            });
        });
    });
}; */


if ($('.directorist-tab')) {
  pureScriptTab('.directorist-tab');
}
/* pureScriptTab('.directorist-user-dashboard-tab');
pureScriptTabChild('.atbdp-bookings-tab');
pureScriptTabChild2('.atbdp-bookings-tab-inner'); */

/***/ }),

/***/ "./assets/src/js/public/components/tab.js":
/*!************************************************!*\
  !*** ./assets/src/js/public/components/tab.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// on load of the page: switch to the currently selected tab
var tab_url = window.location.href.split("/").pop();

if (tab_url.startsWith("#active_")) {
  var urlId = tab_url.split("#").pop().split("active_").pop();

  if (urlId !== 'my_listings') {
    document.querySelector("a[target=".concat(urlId, "]")).click();
  }
}

/***/ }),

/***/ "./assets/src/js/public/modules/dashboard.js":
/*!***************************************************!*\
  !*** ./assets/src/js/public/modules/dashboard.js ***!
  \***************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_dashboard_dashboardSidebar__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/dashboard/dashboardSidebar */ "./assets/src/js/public/components/dashboard/dashboardSidebar.js");
/* harmony import */ var _components_dashboard_dashboardSidebar__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardSidebar__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_dashboard_dashboardTab__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/dashboard/dashboardTab */ "./assets/src/js/public/components/dashboard/dashboardTab.js");
/* harmony import */ var _components_dashboard_dashboardTab__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardTab__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_dashboard_dashboardListing__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/dashboard/dashboardListing */ "./assets/src/js/public/components/dashboard/dashboardListing.js");
/* harmony import */ var _components_dashboard_dashboardListing__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardListing__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_dashboard_dashBoardMoreBtn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/dashboard/dashBoardMoreBtn */ "./assets/src/js/public/components/dashboard/dashBoardMoreBtn.js");
/* harmony import */ var _components_dashboard_dashBoardMoreBtn__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashBoardMoreBtn__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _components_dashboard_dashboardResponsive__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../components/dashboard/dashboardResponsive */ "./assets/src/js/public/components/dashboard/dashboardResponsive.js");
/* harmony import */ var _components_dashboard_dashboardResponsive__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardResponsive__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _components_dashboard_dashboardAnnouncement__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../components/dashboard/dashboardAnnouncement */ "./assets/src/js/public/components/dashboard/dashboardAnnouncement.js");
/* harmony import */ var _components_dashboard_dashboardAnnouncement__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardAnnouncement__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _components_dashboard_dashboardBecomeAuthor__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../components/dashboard/dashboardBecomeAuthor */ "./assets/src/js/public/components/dashboard/dashboardBecomeAuthor.js");
/* harmony import */ var _components_dashboard_dashboardBecomeAuthor__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_components_dashboard_dashboardBecomeAuthor__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _components_pureScriptTab__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../components/pureScriptTab */ "./assets/src/js/public/components/pureScriptTab.js");
/* harmony import */ var _components_pureScriptTab__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_components_pureScriptTab__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _components_profileForm__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../components/profileForm */ "./assets/src/js/public/components/profileForm.js");
/* harmony import */ var _components_profileForm__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_components_profileForm__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _components_tab__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../components/tab */ "./assets/src/js/public/components/tab.js");
/* harmony import */ var _components_tab__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_components_tab__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../components/directoristDropdown */ "./assets/src/js/public/components/directoristDropdown.js");
/* harmony import */ var _components_directoristDropdown__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_components_directoristDropdown__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../components/directoristSelect */ "./assets/src/js/public/components/directoristSelect.js");
/* harmony import */ var _components_directoristSelect__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_components_directoristSelect__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var _components_legacy_support__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../components/legacy-support */ "./assets/src/js/public/components/legacy-support.js");
/* harmony import */ var _components_legacy_support__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_components_legacy_support__WEBPACK_IMPORTED_MODULE_12__);
/* Shamim Ahmed */
console.log("It's a beautiful day!"); // Dashboard Js







 // General Components








/***/ }),

/***/ 6:
/*!*********************************************************!*\
  !*** multi ./assets/src/js/public/modules/dashboard.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! ./assets/src/js/public/modules/dashboard.js */"./assets/src/js/public/modules/dashboard.js");


/***/ })

/******/ });
//# sourceMappingURL=dashboard.js.map