/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assests/main.js":
/*!*************************!*\
  !*** ./assests/main.js ***!
  \*************************/
/***/ (() => {

/*global aff_tab_var */
jQuery(function ($) {
  // Attribute Tables.
  // Initial order.
  var woocommerce_aff_items = $('.product_aff_cf').find('.woocommerce_aff').get();
  woocommerce_aff_items.sort(function (a, b) {
    var compA = parseInt($(a).attr('rel'), 10);
    var compB = parseInt($(b).attr('rel'), 10);
    return compA < compB ? -1 : compA > compB ? 1 : 0;
  });
  $(woocommerce_aff_items).each(function (index, el) {
    $('.product_aff_cf').append(el);
  });

  function aff_row_indexes() {
    $('.product_aff_cf .woocommerce_aff').each(function (index, el) {
      $('.aff_position', el).val(parseInt($(el).index('.product_aff_cf .woocommerce_aff'), 10));
    });
  } // Add rows.


  $('button.add_aff').on('click', function () {
    var size = $('.product_aff_cf .woocommerce_aff').length;
    var $wrapper = $(this).closest('#product_aff_cf');
    var $aff = $wrapper.find('.product_aff_cf');
    var product_type = $('select#product-type').val();
    var data = {
      action: 'woocommerce_add_aff',
      i: size,
      security: aff_tab_var.add_aff_nonce
    };
    $wrapper.block({
      message: null,
      overlayCSS: {
        background: '#fff',
        opacity: 0.6
      }
    });
    $.post(woocommerce_admin_meta_boxes.ajax_url, data, function (response) {
      $aff.append(response);
      aff_row_indexes();
      $aff.find('.woocommerce_aff').last().find('h3').trigger('click');
      $wrapper.unblock();
      $(document.body).trigger('woocommerce_added_aff');
    });
    return false;
  });
  $('.product_aff_cf').on('blur', 'input.aff_name', function () {
    $(this).closest('.woocommerce_aff').find('strong.aff_name').text($(this).val());
  });
  $('.product_aff_cf').on('click', '.remove_row', function () {
    if (window.confirm(aff_tab_var.remove_aff)) {
      var $parent = $(this).parent().parent();
      $parent.find('select, input[type=text]').val('');
      $parent.hide();
      aff_row_indexes();
    }

    return false;
  }); // Attribute ordering.

  $('.product_aff_cf').sortable({
    items: '.woocommerce_aff',
    cursor: 'move',
    axis: 'y',
    handle: 'h3',
    scrollSensitivity: 40,
    forcePlaceholderSize: true,
    helper: 'clone',
    opacity: 0.65,
    placeholder: 'wc-metabox-sortable-placeholder',
    start: function start(event, ui) {
      ui.item.css('background-color', '#f6f6f6');
    },
    stop: function stop(event, ui) {
      ui.item.removeAttr('style');
      aff_row_indexes();
    }
  }); // Save attributes and update variations.

  $('.save_aff_cf').on('click', function () {
    $('.product_aff_cf').block({
      message: null,
      overlayCSS: {
        background: '#fff',
        opacity: 0.6
      }
    });
    var original_data = $('.product_aff_cf').find('input, select, textarea');
    var data = {
      post_id: woocommerce_admin_meta_boxes.post_id,
      product_type: $('#product-type').val(),
      data: original_data.serialize(),
      action: 'woocommerce_save_aff',
      security: aff_tab_var.save_aff_nonce
    };
    $.post(woocommerce_admin_meta_boxes.ajax_url, data, function (response) {
      if (response.error) {
        // Error.
        window.alert(response.error);
      } else if (response.data) {
        // Success.
        $('.product_aff_cf').html(response.data.html);
        $('.product_aff_cf').unblock(); // Hide the 'Used for variations' checkbox if not viewing a variable product

        show_and_hide_panels(); // Reload variations panel.

        var this_page = window.location.toString();
        this_page = this_page.replace('post-new.php?', 'post.php?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&');
      }
    });
  });
});

/***/ }),

/***/ "./assests/main.scss":
/*!***************************!*\
  !*** ./assests/main.scss ***!
  \***************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/public/main": 0,
/******/ 			"public/main": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkaff_links"] = self["webpackChunkaff_links"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["public/main"], () => (__webpack_require__("./assests/main.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["public/main"], () => (__webpack_require__("./assests/main.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;