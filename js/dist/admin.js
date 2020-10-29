module.exports =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./admin.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./admin.js":
/*!******************!*\
  !*** ./admin.js ***!
  \******************/
/*! exports provided: utils */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _src_admin__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./src/admin */ "./src/admin/index.js");
/* empty/unused harmony star reexport *//* harmony import */ var _src_common__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./src/common */ "./src/common/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "utils", function() { return _src_common__WEBPACK_IMPORTED_MODULE_1__["utils"]; });




/***/ }),

/***/ "./src/admin/index.js":
/*!****************************!*\
  !*** ./src/admin/index.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _fof_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @fof-components */ "@fof-components");
/* harmony import */ var _fof_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_fof_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _common_utils_followingPageOptions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../common/utils/followingPageOptions */ "./src/common/utils/followingPageOptions.js");


var SettingsModal = _fof_components__WEBPACK_IMPORTED_MODULE_0__["settings"].SettingsModal,
    SelectItem = _fof_components__WEBPACK_IMPORTED_MODULE_0__["settings"].items.SelectItem;
app.initializers.add('fof/follow-tags', function () {
  app.extensionSettings['fof-follow-tags'] = function () {
    return app.modal.show(SettingsModal, {
      title: app.translator.trans('fof-follow-tags.admin.settings.title'),
      type: 'small',
      items: function items(s) {
        return [m("div", {
          className: "Form-group"
        }, m("label", null, app.translator.trans('fof-follow-tags.admin.settings.following_page_default_label')), SelectItem.component({
          options: Object(_common_utils_followingPageOptions__WEBPACK_IMPORTED_MODULE_1__["default"])('admin.settings'),
          name: 'fof-follow-tags.following_page_default',
          setting: s,
          "default": 'none',
          required: true
        }))];
      }
    });
  };
});

/***/ }),

/***/ "./src/common/index.js":
/*!*****************************!*\
  !*** ./src/common/index.js ***!
  \*****************************/
/*! exports provided: utils */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "utils", function() { return utils; });
/* harmony import */ var _utils_followingPageOptions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils/followingPageOptions */ "./src/common/utils/followingPageOptions.js");

var utils = {
  followingPageOptions: _utils_followingPageOptions__WEBPACK_IMPORTED_MODULE_0__["default"]
};

/***/ }),

/***/ "./src/common/utils/followingPageOptions.js":
/*!**************************************************!*\
  !*** ./src/common/utils/followingPageOptions.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var opts;
/* harmony default export */ __webpack_exports__["default"] = (function (section) {
  return opts || (opts = ['none', 'tags', 'all'].reduce(function (o, key) {
    o[key] = app.translator.trans("fof-follow-tags." + section + ".following_" + key + "_label");
    return o;
  }, {}));
});

/***/ }),

/***/ "@fof-components":
/*!******************************************************!*\
  !*** external "flarum.extensions['fof-components']" ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.extensions['fof-components'];

/***/ })

/******/ });
//# sourceMappingURL=admin.js.map